<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PremiumController extends Controller
{
    private const PLANS = [
        'monthly' => [
            'name' => 'Monthly',
            'price_inr' => 99,
            'duration_months' => 1,
            'label' => 'Flexible monthly access',
        ],
        'annual' => [
            'name' => 'Annual',
            'price_inr' => 799,
            'duration_months' => 12,
            'label' => 'Best value for regular users',
        ],
    ];

    public function plans(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'plans' => array_map(fn (array $plan, string $key) => [
                'id' => $key,
                'name' => $plan['name'],
                'price_inr' => $plan['price_inr'],
                'duration_months' => $plan['duration_months'],
                'label' => $plan['label'],
            ], self::PLANS, array_keys(self::PLANS)),
            'current' => [
                'is_premium' => (bool) $user->is_premium,
                'premium_plan' => $user->premium_plan,
                'premium_expires_at' => $user->premium_expires_at?->toISOString(),
            ],
        ]);
    }

    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'plan' => 'required|in:monthly,annual',
        ]);

        $planKey = $validated['plan'];
        $plan = self::PLANS[$planKey];
        $user = $request->user();

        $startFrom = $user->premium_expires_at && $user->premium_expires_at->isFuture()
            ? $user->premium_expires_at->copy()
            : now();

        $expiresAt = $startFrom->copy()->addMonthsNoOverflow($plan['duration_months']);

        $user->forceFill([
            'is_premium' => true,
            'premium_plan' => $planKey,
            'premium_expires_at' => $expiresAt,
        ])->save();

        return response()->json([
            'success' => true,
            'message' => "{$plan['name']} Premium activated successfully.",
            'subscription' => [
                'plan' => $planKey,
                'price_inr' => $plan['price_inr'],
                'premium_expires_at' => $expiresAt->toISOString(),
                'is_premium' => true,
            ],
        ]);
    }

    public function cancel(Request $request)
    {
        $user = $request->user();

        if (! $user->is_premium) {
            return response()->json([
                'success' => false,
                'message' => 'No active premium plan found.',
            ], 422);
        }

        $user->forceFill([
            'is_premium' => false,
            'premium_plan' => null,
            'premium_expires_at' => null,
        ])->save();

        return response()->json([
            'success' => true,
            'message' => 'Premium plan cancelled successfully.',
            'subscription' => [
                'plan' => null,
                'premium_expires_at' => null,
                'is_premium' => false,
            ],
        ]);
    }
}
