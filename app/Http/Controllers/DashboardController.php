<?php

namespace App\Http\Controllers;

use App\Models\Pickup;
use App\Models\PointTransaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function stats(Request $request)
    {
        $user = $request->user();

        $completedPickups = Pickup::query()
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->get();

        $recentPickups = Pickup::query()
            ->where('user_id', $user->id)
            ->whereBetween('created_at', [now()->subMonths(5)->startOfMonth(), now()->endOfMonth()])
            ->get();

        $monthlyPickups = $recentPickups
            ->groupBy(fn (Pickup $pickup) => $pickup->created_at?->format('Y-m'))
            ->map(function ($items, $key) {
                $date = now()->createFromFormat('Y-m', $key);

                return [
                    'label' => $date?->format('M') ?? $key,
                    'total' => $items->count(),
                ];
            })
            ->values();

        $wasteDistribution = $completedPickups
            ->groupBy('waste_type')
            ->map(fn ($items, $label) => [
                'label' => $label,
                'total' => $items->count(),
            ])
            ->sortByDesc('total')
            ->values();

        $pointsOverTime = PointTransaction::query()
            ->where('user_id', $user->id)
            ->where('type', 'earned_pickup')
            ->whereBetween('created_at', [now()->subDays(29)->startOfDay(), now()->endOfDay()])
            ->get()
            ->groupBy(fn (PointTransaction $transaction) => $transaction->created_at?->format('Y-m-d'))
            ->map(function ($items, $key) {
                $date = now()->createFromFormat('Y-m-d', $key);

                return [
                    'label' => $date?->format('d M') ?? $key,
                    'total' => $items->sum('points'),
                ];
            })
            ->values();

        return response()->json([
            'success' => true,
            'total_pickups' => $completedPickups->count(),
            'total_points' => (int) ($user->total_points ?? 0),
            'total_weight' => (float) ($completedPickups->sum('actual_weight_kg') ?: 0),
            'rewards_redeemed' => PointTransaction::query()
                ->where('user_id', $user->id)
                ->where('type', 'redeemed')
                ->count(),
            'monthly_pickups' => $monthlyPickups,
            'waste_distribution' => $wasteDistribution,
            'points_over_time' => $pointsOverTime,
        ]);
    }
}
