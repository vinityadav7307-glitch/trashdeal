<?php

namespace App\Http\Controllers;

use App\Models\Pickup;
use App\Models\WasteCollector;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function updateCollectorLocation(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $collector = WasteCollector::where('user_id', $request->user()->id)->firstOrFail();

        $collector->forceFill([
            'current_lat' => $request->latitude,
            'current_lng' => $request->longitude,
            'latitude' => $collector->latitude ?? $request->latitude,
            'longitude' => $collector->longitude ?? $request->longitude,
        ])->save();

        return response()->json([
            'success' => true,
            'message' => 'Collector location updated.',
            'location' => [
                'latitude' => $collector->current_lat,
                'longitude' => $collector->current_lng,
            ],
        ]);
    }

    public function show(Request $request, int $pickupId)
    {
        $pickup = Pickup::with(['collector.user', 'user'])
            ->findOrFail($pickupId);

        $authedUser = $request->user();
        $collector = $pickup->collector;

        $canView = $pickup->user_id === $authedUser->id
            || $pickup->user?->role === 'collector'
            || $collector?->user_id === $authedUser->id
            || $authedUser->role === 'admin';

        if (! $canView) {
            return response()->json([
                'success' => false,
                'message' => 'You are not allowed to track this pickup.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'pickup_id' => $pickup->id,
            'status' => $pickup->status,
            'collector_name' => $collector?->name,
            'collector_lat' => $collector?->current_lat ?? $collector?->latitude,
            'collector_lng' => $collector?->current_lng ?? $collector?->longitude,
            'user_lat' => $pickup->pickup_lat ?? $pickup->user?->latitude,
            'user_lng' => $pickup->pickup_lng ?? $pickup->user?->longitude,
            'address' => $pickup->address,
            'scheduled_at' => $pickup->scheduled_at?->toISOString(),
        ]);
    }
}
