<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use App\Models\WasteScan;
use App\Models\Pickup;
use App\Models\WasteCollector;
use Illuminate\Http\Request;

class ScanController extends Controller
{
    // Points awarded per kg for each waste category
    private array $pointsMap = [
        'organic' => 10,
        'plastic' => 10,
        'metal' => 10,
        'glass' => 10,
    ];

    // Keyword-based classifier (works without any ML service)
    private array $wasteKeywords = [
        'organic' => ['food', 'kitchen', 'vegetable', 'fruit', 'leaf', 'garden', 'compost', 'banana', 'rice'],
        'plastic' => ['plastic', 'bottle', 'packaging', 'wrapper', 'container'],
        'metal' => ['metal', 'can', 'aluminium', 'tin', 'steel'],
        'glass' => ['glass', 'jar', 'bottle glass'],
    ];

    // POST /api/scan/waste  — camera scans waste image
    public function scanWaste(Request $request)
    {
        $request->validate([
            'image'      => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'pickup_id'  => 'nullable|exists:pickups,id',
            'detected_type' => 'nullable|in:plastic,organic,metal,glass',
            'confidence' => 'nullable|numeric|min:0|max:100',
        ]);

        // Save the uploaded image
        $path = $request->file('image')->store('scans/waste', 'public');

        $result = $request->filled('detected_type')
            ? $this->fromFrontendDetection($request->string('detected_type')->value(), (float) $request->input('confidence', 0))
            : ($this->callMlApi($path) ?? $this->fallbackClassify($request->file('image')->getClientOriginalName()));

        $pointsAwarded = $this->pointsMap[$result['category']] ?? 0;

        // Save scan record
        $scan = WasteScan::create([
            'user_id'        => $request->user()->id,
            'pickup_id'      => $request->pickup_id,
            'scan_type'      => 'waste_identification',
            'image_path'     => $path,
            'detected_waste' => $result['label'],
            'waste_category' => $result['category'],
            'confidence'     => $result['confidence'],
            'points_awarded' => $pointsAwarded,
            'ml_raw_response'=> json_encode($result),
            'scanned_at'     => now(),
        ]);

        return response()->json([
            'success'        => true,
            'scan_id'        => $scan->id,
            'detected_waste' => $result['label'],
            'category'       => $result['category'],
            'confidence'     => $result['confidence'],
            'pickup_waste_type' => $this->pickupWasteType($result['category']),
            'points_preview' => $pointsAwarded,
            'image_url'      => asset('storage/' . $path),
            'message'        => "Detected: {$result['label']}. We filled the closest waste type for your pickup.",
            'tip'            => $this->getTip($result['category']),
        ]);
    }

    // POST /api/scan/collector-qr  — user scans collector QR badge
    public function scanCollectorQR(Request $request)
    {
        if ($request->filled('pickup_id') || $request->filled('qr_token')) {
            $request->validate([
                'pickup_id' => 'required|exists:pickups,id',
                'qr_token' => 'required|string',
            ]);

            $collector = WasteCollector::where('user_id', $request->user()->id)->first();

            if (! $collector) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only collectors can verify a pickup QR.',
                ], 403);
            }

            $pickup = Pickup::where('id', $request->pickup_id)
                ->where('collector_id', $collector->id)
                ->first();

            if (! $pickup) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pickup not found for this collector.',
                ], 404);
            }

            if (! hash_equals((string) $pickup->qr_token, (string) $request->qr_token)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid QR token. Please scan the correct pickup code.',
                ], 422);
            }

            $pickup->forceFill([
                'qr_verified_at' => now(),
            ])->save();

            WasteScan::create([
                'user_id' => $request->user()->id,
                'pickup_id' => $pickup->id,
                'scan_type' => 'collector_qr',
                'qr_data' => $request->qr_token,
                'is_verified' => true,
                'verified_by' => $collector->name,
                'scanned_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'verified' => true,
                'pickup_id' => $pickup->id,
                'message' => 'Pickup QR verified. You can complete the pickup now.',
            ]);
        }

        $request->validate([
            'qr_code' => 'required|string',
            'pickup_id' => 'nullable|exists:pickups,id',
        ]);

        $collector = WasteCollector::where('qr_code', $request->qr_code)
            ->where('is_active', true)
            ->first();

        if (! $collector) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or unrecognised collector QR code. Please contact support.'
            ], 404);
        }

        WasteScan::create([
            'user_id' => $request->user()->id,
            'pickup_id' => $request->pickup_id,
            'scan_type' => 'collector_qr',
            'qr_data' => $request->qr_code,
            'is_verified' => true,
            'verified_by' => $collector->name,
            'scanned_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'verified' => true,
            'collector_id' => $collector->id,
            'collector_name' => $collector->name,
            'collector_phone' => $collector->phone,
            'zone' => $collector->zone,
            'message' => "Collector {$collector->name} verified successfully! ✅",
        ]);
    }

    // Try to call external ML API (Python/Flask service)
    private function callMlApi(string $imagePath): ?array
    {
        $mlUrl = config('services.waste_ml.url');
        if (!$mlUrl) return null;

        try {
            $response = Http::timeout(5)->post($mlUrl, [
                'image_path' => $imagePath
            ]);

            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            // ML service unavailable — use fallback
        }

        return null;
    }

    // Fallback: classify by filename keywords
    private function fallbackClassify(string $filename): array
    {
        $filename = strtolower($filename);

        foreach ($this->wasteKeywords as $category => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($filename, $keyword)) {
                    return [
                        'label'      => ucfirst($category),
                        'category'   => $category,
                        'confidence' => 72.0,
                    ];
                }
            }
        }

        // Default: recyclable (most common household waste)
        return [
            'label'      => 'Plastic',
            'category'   => 'plastic',
            'confidence' => 55.0,
        ];
    }

    private function fromFrontendDetection(string $type, float $confidence): array
    {
        $category = strtolower($type);

        return [
            'label' => ucfirst($category),
            'category' => $category,
            'confidence' => round($confidence > 0 ? $confidence : 68.0, 1),
        ];
    }

    private function pickupWasteType(string $category): string
    {
        return match ($category) {
            'organic' => 'organic',
            'plastic', 'metal', 'glass' => 'recyclable',
            default => 'mixed',
        };
    }

    // Helpful tip based on waste category
    private function getTip(string $category): string
    {
        return match ($category) {
            'organic'    => 'Tip: Rinse food waste before handover to avoid pests and odour.',
            'plastic' => 'Tip: Crush plastic bottles and keep them dry before pickup.',
            'metal' => 'Tip: Separate cans and metal pieces for quicker sorting.',
            'glass' => 'Tip: Keep glass items wrapped safely before handover.',
            default      => 'Tip: Segregate waste at source to earn maximum points!',
        };
    }
}
