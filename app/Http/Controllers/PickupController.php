<?php

namespace App\Http\Controllers;

use App\Models\Pickup;
use App\Models\PointTransaction;
use App\Models\User;
use App\Models\WasteCollector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PickupController extends Controller
{
    public static function simulateActualWeight(string $wasteType, mixed $estimatedWeight = null): float
    {
        if (is_numeric($estimatedWeight) && (float) $estimatedWeight > 0) {
            return round((float) $estimatedWeight, 2);
        }

        return match ($wasteType) {
            'organic' => (float) rand(1, 5),
            'recyclable', 'e-waste' => (float) rand(1, 3),
            'inert' => (float) rand(1, 2),
            default => (float) rand(1, 4),
        };
    }

    public static function calculatePoints(Pickup $pickup): int
    {
        $weight = (float) ($pickup->actual_weight_kg ?? $pickup->estimated_weight_kg ?? 0);

        return (int) round($weight * 10);
    }

    private static function ensureCollectorAvailability(Pickup $pickup): void
    {
        if ($pickup->collector_id) {
            return;
        }

        $collector = self::findNearestCollector($pickup->pickup_lat, $pickup->pickup_lng);

        if (! $collector) {
            return;
        }

        $pickup->forceFill([
            'collector_id' => $collector->id,
            'status' => 'assigned',
            'assigned_at' => $pickup->assigned_at ?? now(),
        ])->save();

        $collector->forceFill(['is_available' => false])->save();
    }

    public static function processDuePickupsForUser(User $user): void
    {
        $user->loadMissing('pickups');

        foreach ($user->pickups as $pickup) {
            self::ensureCollectorAvailability($pickup);
        }
    }

    private static function resolvePickupCoordinates(Request $request): array
    {
        $user = $request->user();

        return [
            'lat' => $request->pickup_lat ?? $user->latitude,
            'lng' => $request->pickup_lng ?? $user->longitude,
        ];
    }

    private static function collectorLatitude(WasteCollector $collector): ?float
    {
        return $collector->latitude ?? $collector->current_lat;
    }

    private static function collectorLongitude(WasteCollector $collector): ?float
    {
        return $collector->longitude ?? $collector->current_lng;
    }

    private static function findNearestCollector(mixed $pickupLat, mixed $pickupLng): ?WasteCollector
    {
        if (! is_numeric($pickupLat) || ! is_numeric($pickupLng)) {
            return WasteCollector::query()
                ->where('is_active', true)
                ->where('is_available', true)
                ->orderBy('id')
                ->first();
        }

        $collectors = WasteCollector::query()
            ->where('is_active', true)
            ->where('is_available', true)
            ->get()
            ->filter(function (WasteCollector $collector) {
                return is_numeric(self::collectorLatitude($collector)) && is_numeric(self::collectorLongitude($collector));
            });

        if ($collectors->isEmpty()) {
            return WasteCollector::query()
                ->where('is_active', true)
                ->where('is_available', true)
                ->orderBy('id')
                ->first();
        }

        return $collectors
            ->sortBy(function (WasteCollector $collector) use ($pickupLat, $pickupLng) {
                $lat1 = (float) $pickupLat;
                $lng1 = (float) $pickupLng;
                $lat2 = (float) self::collectorLatitude($collector);
                $lng2 = (float) self::collectorLongitude($collector);

                return sqrt((($lat1 - $lat2) ** 2) + (($lng1 - $lng2) ** 2));
            })
            ->first();
    }

    private static function finalizePickup(Pickup $pickup, ?float $actualWeight = null): array
    {
        $weight = $actualWeight && $actualWeight > 0
            ? round($actualWeight, 2)
            : self::simulateActualWeight($pickup->waste_type, $pickup->estimated_weight_kg);

        $pickup->forceFill([
            'actual_weight_kg' => $weight,
            'points_earned' => 0,
            'status' => 'completed',
            'verified_at' => now(),
            'completed_at' => now(),
        ])->save();

        $pointsEarned = self::calculatePoints($pickup);

        $pickup->forceFill([
            'points_earned' => $pointsEarned,
        ])->save();

        $user = $pickup->user()->firstOrFail();
        $user->increment('total_points', $pointsEarned);

        PointTransaction::firstOrCreate(
            [
                'user_id' => $user->id,
                'pickup_id' => $pickup->id,
                'type' => 'earned_pickup',
            ],
            [
                'points' => $pointsEarned,
                'balance_after' => $user->fresh()->total_points,
                'description' => "Pickup completed: earned {$pointsEarned} pts for {$weight}kg of {$pickup->waste_type} waste",
            ]
        );

        if ($pickup->collector) {
            $pickup->collector->increment('total_pickups');
            $pickup->collector->forceFill(['is_available' => true])->save();
        }

        return ['points' => $pointsEarned, 'weight' => $weight];
    }

    // POST /api/pickups  — user schedules a pickup
    public function schedule(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address'          => 'required|string',
            'waste_type'       => 'required|in:organic,recyclable,e-waste,inert,mixed',
            'scheduled_at'     => 'required|date|after:now',
            'pickup_lat'       => 'nullable|numeric',
            'pickup_lng'       => 'nullable|numeric',
            'estimated_weight_kg' => 'nullable|numeric|min:0.1',
            'notes'            => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        $coordinates = self::resolvePickupCoordinates($request);
        $collector = self::findNearestCollector($coordinates['lat'], $coordinates['lng']);

        $pickup = Pickup::create([
            'user_id'              => $request->user()->id,
            'collector_id'         => $collector?->id,
            'address'              => $request->address,
            'pickup_lat'           => $coordinates['lat'],
            'pickup_lng'           => $coordinates['lng'],
            'waste_type'           => $request->waste_type,
            'estimated_weight_kg'  => $request->estimated_weight_kg,
            'notes'                => $request->notes,
            'scheduled_at'         => $request->scheduled_at,
            'status'               => $collector ? 'assigned' : 'pending',
            'assigned_at'          => $collector ? now() : null,
            'qr_token'             => strtoupper(Str::random(10)),
        ]);

        if ($collector) {
            $collector->forceFill(['is_available' => false])->save();
        }

        return response()->json([
            'success'   => true,
            'message'   => $collector
                ? 'Pickup scheduled and assigned to the nearest collector.'
                : 'Pickup scheduled successfully. We are finding the best collector for you.',
            'points_earned' => 0,
            'pickup'    => $pickup->fresh()->load('collector.user'),
            'collector' => $collector,
        ], 201);
    }

    // GET /api/pickups  — user sees their own pickups
    public function myPickups(Request $request)
    {
        self::processDuePickupsForUser($request->user());

        $pickups = Pickup::where('user_id', $request->user()->id)
                          ->with('collector.user')
                          ->orderByDesc('created_at')
                          ->paginate(10);

        return response()->json([
            'success' => true,
            'pickups' => $pickups
        ]);
    }

    // GET /api/pickups/{id}
    public function show(Request $request, $id)
    {
        self::processDuePickupsForUser($request->user());

        $pickup = Pickup::where('id', $id)
                         ->where('user_id', $request->user()->id)
                         ->with('collector.user')
                         ->firstOrFail();

        return response()->json([
            'success' => true,
            'pickup'  => $pickup
        ]);
    }

    // PATCH /api/pickups/{id}/cancel
    public function cancel(Request $request, $id)
    {
        $pickup = Pickup::where('id', $id)
                         ->where('user_id', $request->user()->id)
                         ->whereIn('status', ['pending', 'assigned'])
                         ->firstOrFail();

        $pickup->update(['status' => 'cancelled']);

        return response()->json([
            'success' => true,
            'message' => 'Pickup cancelled successfully.'
        ]);
    }

    // GET /api/assigned-pickups  — collector sees their jobs
    public function assignedPickups(Request $request)
    {
        $collector = WasteCollector::where('user_id', $request->user()->id)->firstOrFail();

        $pickups = Pickup::where('collector_id', $collector->id)
                          ->whereIn('status', ['assigned', 'in_progress', 'completed'])
                          ->with('user')
                          ->orderBy('scheduled_at')
                          ->get();

        return response()->json([
            'success' => true,
            'pickups' => $pickups
        ]);
    }

    // PATCH /api/pickups/{id}/start  — collector starts the pickup journey
    public function startPickup(Request $request, $id)
    {
        $collector = WasteCollector::where('user_id', $request->user()->id)->firstOrFail();

        $pickup = Pickup::where('id', $id)
            ->where('collector_id', $collector->id)
            ->where('status', 'assigned')
            ->firstOrFail();

        if ($pickup->scheduled_at && now()->lt($pickup->scheduled_at)) {
            return response()->json([
                'success' => false,
                'message' => 'Pickup can only be completed after scheduled time',
            ], 422);
        }

        $pickup->forceFill([
            'status' => 'in_progress',
            'picked_at' => now(),
        ])->save();

        return response()->json([
            'success' => true,
            'message' => 'Pickup started. Scan the pickup QR to continue.',
            'pickup' => $pickup->fresh(),
        ]);
    }

    // PATCH /api/pickups/{id}/complete  — collector completes the pickup
    public function completePickup(Request $request, $id)
    {
        $collector = WasteCollector::where('user_id', $request->user()->id)->firstOrFail();

        $pickup = Pickup::where('id', $id)
            ->where('collector_id', $collector->id)
            ->whereIn('status', ['assigned', 'in_progress'])
            ->firstOrFail();

        if ($pickup->scheduled_at && now()->lt($pickup->scheduled_at)) {
            return response()->json([
                'success' => false,
                'message' => 'Pickup can only be completed after scheduled time',
            ], 422);
        }

        if (! $pickup->qr_verified_at) {
            return response()->json([
                'success' => false,
                'message' => 'Scan the pickup QR before completing this pickup.',
            ], 422);
        }

        $pickup->forceFill([
            'picked_at' => $pickup->picked_at ?? now(),
        ])->save();

        $result = self::finalizePickup($pickup, $pickup->actual_weight_kg ?: null);

        return response()->json([
            'success' => true,
            'message' => "Pickup completed successfully. {$result['points']} points added.",
            'points_earned' => $result['points'],
            'pickup' => $pickup->fresh()->load('user', 'collector.user'),
        ]);
    }

    // POST /api/pickups/{id}/weigh  — collector records actual weight & awards points
    public function recordWeight(Request $request, $id)
    {
        $request->validate([
            'actual_weight_kg' => 'required|numeric|min:0.1',
            'pickup_photo'     => 'nullable|image|max:5120',
        ]);

        $collector = WasteCollector::where('user_id', $request->user()->id)->firstOrFail();
        $pickup = Pickup::where('id', $id)
            ->where('collector_id', $collector->id)
            ->whereIn('status', ['assigned', 'in_progress'])
            ->firstOrFail();

        if ($pickup->scheduled_at && now()->lt($pickup->scheduled_at)) {
            return response()->json([
                'success' => false,
                'message' => 'Pickup can only be completed after scheduled time',
            ], 422);
        }

        if (! $pickup->qr_verified_at) {
            return response()->json([
                'success' => false,
                'message' => 'Scan the pickup QR before completing this pickup.',
            ], 422);
        }

        $photoPath = null;
        if ($request->hasFile('pickup_photo')) {
            $photoPath = $request->file('pickup_photo')->store('pickups', 'public');
        }

        $pickup->forceFill([
            'pickup_photo' => $photoPath,
            'picked_at' => $pickup->picked_at ?? now(),
        ])->save();

        $result = self::finalizePickup($pickup, (float) $request->actual_weight_kg);

        return response()->json([
            'success'       => true,
            'message'       => "Pickup verified! User earned {$result['points']} points.",
            'points_earned' => $result['points'],
            'pickup'        => $pickup->fresh(),
        ]);
    }
}
