<?php

namespace App\Http\Controllers;

use App\Models\PointTransaction;
use Illuminate\Http\Request;

class PointsController extends Controller
{
    // GET /api/points
    public function balance(Request $request)
    {
        $user = $request->user();
        $user = $user->fresh();

        return response()->json([
            'points' => $user->total_points ?? 0,
        ]);
    }

    // GET /api/points/history
    public function history(Request $request)
    {
        $transactions = PointTransaction::where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'transactions' => $transactions,
        ]);
    }
}
