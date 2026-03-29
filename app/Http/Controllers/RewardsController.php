<?php

namespace App\Http\Controllers;

use App\Models\PointTransaction;
use App\Models\Reward;
use Illuminate\Http\Request;

class RewardsController extends Controller
{
    private function normalizeCategory(string $category): string
    {
        return match (true) {
            str_contains($category, 'grocery') => 'grocery',
            str_contains($category, 'eco'), str_contains($category, 'donation'), str_contains($category, 'hamper') => 'eco',
            str_contains($category, 'voucher'), str_contains($category, 'amazon'), str_contains($category, 'fuel'), str_contains($category, 'bus') => 'voucher',
            default => 'general',
        };
    }

    // GET /api/rewards
    public function index(Request $request)
    {
        $user = $request->user();
        PickupController::processDuePickupsForUser($user);
        $user = $user->fresh();

        $query = Reward::query()
            ->where('is_active', true)
            ->where(function ($query) {
                $query->where('stock', -1)->orWhere('stock', '>', 0);
            });

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $rewards = $query
            ->orderBy('points_required')
            ->get()
            ->map(function (Reward $reward) use ($user) {
                $pointsRemaining = max(0, $reward->points_required - $user->total_points);
                $isPremiumLocked = $reward->is_premium_only && !$user->is_premium;

                return [
                    'id' => $reward->id,
                    'name' => $reward->title,
                    'points_required' => $reward->points_required,
                    'category' => $this->normalizeCategory($reward->category),
                    'is_locked' => $pointsRemaining > 0 || $isPremiumLocked,
                    'can_redeem' => $pointsRemaining === 0 && !$isPremiumLocked,
                    'points_remaining' => $pointsRemaining,
                    'is_premium_only' => (bool) $reward->is_premium_only,
                    'lock_reason' => $isPremiumLocked
                        ? 'Premium required'
                        : ($pointsRemaining > 0 ? 'Not enough points' : null),
                ];
            })
            ->values();

        return response()->json([
            'success' => true,
            'rewards' => $rewards,
        ]);
    }

    // POST /api/rewards/{id}/redeem
    public function redeem(Request $request, int $id)
    {
        $user = $request->user();
        $reward = Reward::where('is_active', true)->findOrFail($id);

        if ($reward->is_premium_only && !$user->is_premium) {
            return response()->json([
                'success' => false,
                'message' => 'This reward is for Premium members only.',
            ], 403);
        }

        if ($user->total_points < $reward->points_required) {
            return response()->json([
                'message' => 'Not enough points',
            ], 400);
        }

        if ($reward->stock !== -1 && $reward->stock <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'This reward is out of stock.',
            ], 422);
        }

        $user->decrement('total_points', $reward->points_required);

        if ($reward->stock !== -1) {
            $reward->decrement('stock');
        }

        $referenceCode = strtoupper('TD-' . uniqid());

        PointTransaction::create([
            'user_id' => $user->id,
            'reward_id' => $reward->id,
            'type' => 'redeemed',
            'points' => -$reward->points_required,
            'balance_after' => $user->fresh()->total_points,
            'description' => "Redeemed reward: {$reward->title}",
            'reference_code' => $referenceCode,
            'status' => 'completed',
        ]);

        return response()->json([
            'success' => true,
            'message' => "Successfully redeemed {$reward->title}.",
            'reference_code' => $referenceCode,
            'points_spent' => $reward->points_required,
            'points_left' => $user->fresh()->total_points,
            'reward' => [
                'id' => $reward->id,
                'name' => $reward->title,
                'points_required' => $reward->points_required,
            ],
        ]);
    }
}
