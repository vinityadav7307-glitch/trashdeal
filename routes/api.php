<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PickupController;
use App\Http\Controllers\PointsController;
use App\Http\Controllers\PremiumController;
use App\Http\Controllers\RewardsController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;


// Fix: Laravel 12 requires a named login route
// Route::get('/login', function () {
//     return response()->json(['message' => 'Please login via POST /api/login'], 401);
// })->name('login');
// Temporary debug route - no controller, no DB
Route::get('/test-auth', function () {
    return response()->json([
        'message' => 'Auth works!',
        'user_id' => auth()->id(),
    ]);
})->middleware('auth:sanctum');
// ── Public routes ─────────────────────────────────────────────────────────────
Route::post('/register',   [AuthController::class, 'register']);
Route::post('/login',      [AuthController::class, 'login']);
Route::post('/send-otp',   [AuthController::class, 'sendOtp']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);

// ── Authenticated user routes ─────────────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {


    // Profile
    Route::get('/profile',  [AuthController::class, 'profile']);
    Route::post('/logout',  [AuthController::class, 'logout']);

    // Pickups
    Route::post('/pickups',                [PickupController::class, 'schedule']);
    Route::get('/pickups',                 [PickupController::class, 'myPickups']);
    Route::get('/pickups/{id}',            [PickupController::class, 'show']);
    Route::patch('/pickups/{id}/cancel',   [PickupController::class, 'cancel']);

    // Points & Rewards
    Route::get('/dashboard/stats',         [DashboardController::class, 'stats']);
    Route::get('/points',                  [PointsController::class, 'balance']);
    Route::get('/points/history',          [PointsController::class, 'history']);
    Route::get('/premium/plans',           [PremiumController::class, 'plans']);
    Route::post('/premium/subscribe',      [PremiumController::class, 'subscribe']);
    Route::post('/premium/cancel',         [PremiumController::class, 'cancel']);
    Route::get('/rewards',                 [RewardsController::class, 'index']);
    Route::post('/rewards/{id}/redeem',    [RewardsController::class, 'redeem']);

    // Camera scan
    Route::post('/scan/waste',             [ScanController::class, 'scanWaste']);
    Route::post('/scan/collector-qr',      [ScanController::class, 'scanCollectorQR']);
    Route::get('/track/{pickup}',          [TrackingController::class, 'show']);
});

// ── Waste collector routes ────────────────────────────────────────────────────
Route::middleware(['auth:sanctum', 'role:collector'])->group(function () {
    Route::get('/assigned-pickups',        [PickupController::class, 'assignedPickups']);
    Route::post('/collector/location',     [TrackingController::class, 'updateCollectorLocation']);
    Route::patch('/pickups/{id}/start',    [PickupController::class, 'startPickup']);
    Route::patch('/pickups/{id}/complete', [PickupController::class, 'completePickup']);
    Route::post('/pickups/{id}/weigh',     [PickupController::class, 'recordWeight']);
});

// ── Admin routes ──────────────────────────────────────────────────────────────
Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard',               [AdminController::class, 'dashboard']);
    Route::get('/users',                   [AdminController::class, 'users']);
    Route::get('/collectors',              [AdminController::class, 'collectors']);
    Route::post('/collectors',             [AdminController::class, 'createCollector']);
    Route::get('/pickups',                 [AdminController::class, 'allPickups']);
    Route::get('/reports',                 [AdminController::class, 'reports']);
});
