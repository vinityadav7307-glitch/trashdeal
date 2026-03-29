<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pickup;
use App\Models\WasteCollector;
use App\Models\PointTransaction;
use App\Models\Reward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // GET /api/admin/dashboard
    public function dashboard()
    {
        return response()->json([
            'success' => true,
            'stats'   => [
                'total_users'       => User::where('role', 'user')->count(),
                'total_collectors'  => WasteCollector::count(),
                'total_pickups'     => Pickup::count(),
                'pending_pickups'   => Pickup::where('status', 'pending')->count(),
                'completed_pickups' => Pickup::where('status', 'completed')->count(),
                'total_points_issued' => PointTransaction::where('type', 'earned_pickup')->sum('points'),
                'total_redeemed'    => PointTransaction::where('type', 'redeemed')->count(),
                'premium_users'     => User::where('is_premium', true)->count(),
                'waste_this_month'  => Pickup::where('status', 'completed')
                                              ->whereMonth('completed_at', now()->month)
                                              ->sum('actual_weight_kg'),
            ],
            'recent_pickups' => Pickup::with(['user', 'collector'])
                                       ->orderByDesc('created_at')
                                       ->limit(10)
                                       ->get(),
        ]);
    }

    // GET /api/admin/users
    public function users(Request $request)
    {
        $users = User::where('role', 'user')
                      ->when($request->search, fn($q) =>
                          $q->where('name', 'like', "%{$request->search}%")
                            ->orWhere('phone', 'like', "%{$request->search}%")
                      )
                      ->orderByDesc('total_points')
                      ->paginate(20);

        return response()->json(['success' => true, 'users' => $users]);
    }

    // GET /api/admin/collectors
    public function collectors()
    {
        $collectors = WasteCollector::with('user')
                                     ->withCount('pickups')
                                     ->orderByDesc('total_pickups')
                                     ->get();

        return response()->json(['success' => true, 'collectors' => $collectors]);
    }

    // POST /api/admin/collectors  — create a new collector account
    public function createCollector(Request $request)
    {
        $request->validate([
            'name'  => 'required|string',
            'phone' => 'required|string|unique:users,phone',
            'zone'  => 'required|string',
        ]);

        // Create user account for collector
        $user = User::create([
            'name'     => $request->name,
            'phone'    => $request->phone,
            'password' => Hash::make('TrashDeal@123'), // default password
            'role'     => 'collector',
        ]);

        // Generate unique QR code
        $qrCode = 'TDC-' . strtoupper(uniqid());

        $collector = WasteCollector::create([
            'user_id'      => $user->id,
            'name'         => $request->name,
            'phone'        => $request->phone,
            'qr_code'      => $qrCode,
            'zone'         => $request->zone,
            'vehicle_type' => $request->vehicle_type,
            'is_active'    => true,
        ]);

        return response()->json([
            'success'        => true,
            'message'        => 'Collector created. Default password: TrashDeal@123',
            'collector'      => $collector,
            'qr_code'        => $qrCode,
        ], 201);
    }

    // GET /api/admin/pickups
    public function allPickups(Request $request)
    {
        $pickups = Pickup::with(['user', 'collector'])
                          ->when($request->status, fn($q) => $q->where('status', $request->status))
                          ->when($request->waste_type, fn($q) => $q->where('waste_type', $request->waste_type))
                          ->orderByDesc('created_at')
                          ->paginate(20);

        return response()->json(['success' => true, 'pickups' => $pickups]);
    }

    // GET /api/admin/reports
    public function reports(Request $request)
    {
        $from = $request->from ?? now()->startOfMonth();
        $to   = $request->to   ?? now();

        return response()->json([
            'success' => true,
            'period'  => ['from' => $from, 'to' => $to],
            'report'  => [
                'pickups_completed' => Pickup::where('status', 'completed')
                                              ->whereBetween('completed_at', [$from, $to])
                                              ->count(),
                'total_waste_kg'    => Pickup::where('status', 'completed')
                                              ->whereBetween('completed_at', [$from, $to])
                                              ->sum('actual_weight_kg'),
                'points_issued'     => PointTransaction::where('type', 'earned_pickup')
                                                        ->whereBetween('created_at', [$from, $to])
                                                        ->sum('points'),
                'rewards_redeemed'  => PointTransaction::where('type', 'redeemed')
                                                        ->whereBetween('created_at', [$from, $to])
                                                        ->count(),
                'waste_by_type'     => Pickup::where('status', 'completed')
                                              ->whereBetween('completed_at', [$from, $to])
                                              ->groupBy('waste_type')
                                              ->selectRaw('waste_type, SUM(actual_weight_kg) as total_kg, COUNT(*) as count')
                                              ->get(),
                'new_users'         => User::whereBetween('created_at', [$from, $to])->count(),
            ],
        ]);
    }
}
