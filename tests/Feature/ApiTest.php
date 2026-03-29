<?php

namespace Tests\Feature;

use App\Models\Pickup;
use App\Models\PointTransaction;
use App\Models\Reward;
use App\Models\User;
use App\Models\WasteCollector;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_profile_request_returns_json_401(): void
    {
        $response = $this->getJson('/api/profile');

        $response
            ->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Unauthenticated. Please login first.',
            ]);
    }

    public function test_user_can_register_login_fetch_profile_and_logout(): void
    {
        $register = $this->postJson('/api/register', [
            'name' => 'Test User',
            'phone' => '9999999999',
            'email' => 'test@example.com',
            'password' => 'secret123',
        ]);

        $register
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('user.phone', '9999999999');

        $login = $this->postJson('/api/login', [
            'login' => '9999999999',
            'password' => 'secret123',
        ]);

        $token = $login->json('token');

        $login
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/profile')
            ->assertOk()
            ->assertJsonPath('user.email', 'test@example.com');

        $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/logout')
            ->assertOk()
            ->assertJsonPath('success', true);
    }

    public function test_user_can_schedule_and_view_pickups(): void
    {
        $user = User::create([
            'name' => 'Pickup User',
            'phone' => '9000000001',
            'email' => 'pickup@example.com',
            'password' => Hash::make('secret123'),
            'role' => 'user',
            'latitude' => 28.6139,
            'longitude' => 77.2090,
        ]);

        $collectorUser = User::create([
            'name' => 'Nearby Collector User',
            'phone' => '9000000011',
            'email' => 'nearbycollector@example.com',
            'password' => Hash::make('secret123'),
            'role' => 'collector',
        ]);

        $collector = WasteCollector::create([
            'user_id' => $collectorUser->id,
            'name' => 'Nearby Collector',
            'phone' => '9000000011',
            'qr_code' => 'TDC-LOC-001',
            'zone' => 'Zone A',
            'is_active' => true,
            'is_available' => true,
            'latitude' => 28.6140,
            'longitude' => 77.2091,
        ]);

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/pickups', [
            'address' => 'Main Street',
            'waste_type' => 'recyclable',
            'scheduled_at' => now()->addMinute()->toISOString(),
            'pickup_lat' => 28.61395,
            'pickup_lng' => 77.20905,
            'estimated_weight_kg' => 3.5,
            'notes' => 'Near gate',
        ])
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('pickup.address', 'Main Street')
            ->assertJsonPath('pickup.status', 'assigned')
            ->assertJsonPath('collector.id', $collector->id)
            ->assertJsonPath('points_earned', 0);

        $this->assertNotNull($response->json('pickup.qr_token'));

        $this->getJson('/api/pickups')
            ->assertOk()
            ->assertJsonCount(1, 'pickups.data');

        $this->getJson('/api/points')
            ->assertOk()
            ->assertJsonPath('points', 0);

        $this->travel(2)->minutes();

        $this->getJson('/api/points')
            ->assertOk()
            ->assertJsonPath('points', 0);
    }

    public function test_user_can_view_and_redeem_rewards(): void
    {
        $user = User::create([
            'name' => 'Reward User',
            'phone' => '9000000002',
            'email' => 'reward@example.com',
            'password' => Hash::make('secret123'),
            'role' => 'user',
            'total_points' => 500,
        ]);

        $reward = Reward::create([
            'title' => 'Amazon Voucher',
            'category' => 'amazon_voucher',
            'points_required' => 200,
            'stock' => 10,
            'is_active' => true,
            'is_premium_only' => false,
        ]);

        Sanctum::actingAs($user);

        $this->getJson('/api/rewards')
            ->assertOk()
            ->assertJsonCount(1, 'rewards')
            ->assertJsonPath('rewards.0.category', 'voucher')
            ->assertJsonPath('rewards.0.can_redeem', true)
            ->assertJsonPath('rewards.0.is_locked', false)
            ->assertJsonPath('rewards.0.points_remaining', 0);

        $this->postJson("/api/rewards/{$reward->id}/redeem")
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('points_spent', 200);

        $this->assertDatabaseHas('point_transactions', [
            'user_id' => $user->id,
            'reward_id' => $reward->id,
            'type' => 'redeemed',
        ]);
    }

    public function test_user_can_view_dashboard_stats_and_tracking(): void
    {
        $user = User::create([
            'name' => 'Analytics User',
            'phone' => '9000000021',
            'email' => 'analytics@example.com',
            'password' => Hash::make('secret123'),
            'role' => 'user',
            'total_points' => 120,
            'latitude' => 28.6139,
            'longitude' => 77.2090,
        ]);

        $collectorUser = User::create([
            'name' => 'Tracking Collector',
            'phone' => '9000000022',
            'email' => 'trackingcollector@example.com',
            'password' => Hash::make('secret123'),
            'role' => 'collector',
        ]);

        $collector = WasteCollector::create([
            'user_id' => $collectorUser->id,
            'name' => 'Tracking Collector',
            'phone' => '9000000022',
            'qr_code' => 'TDC-TRACK-001',
            'zone' => 'Zone T',
            'is_active' => true,
            'is_available' => true,
            'current_lat' => 28.6145,
            'current_lng' => 77.2101,
        ]);

        $pickup = Pickup::create([
            'user_id' => $user->id,
            'collector_id' => $collector->id,
            'address' => 'Tracked Address',
            'pickup_lat' => 28.6139,
            'pickup_lng' => 77.2090,
            'waste_type' => 'recyclable',
            'status' => 'completed',
            'actual_weight_kg' => 4,
            'points_earned' => 40,
            'scheduled_at' => now()->subHour(),
            'completed_at' => now()->subMinutes(10),
        ]);

        PointTransaction::create([
            'user_id' => $user->id,
            'pickup_id' => $pickup->id,
            'type' => 'earned_pickup',
            'points' => 40,
            'balance_after' => 120,
            'description' => 'Pickup completed',
        ]);

        Sanctum::actingAs($user);

        $this->getJson('/api/dashboard/stats')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('total_pickups', 1)
            ->assertJsonPath('total_weight', 4);

        $this->getJson("/api/track/{$pickup->id}")
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('collector_name', 'Tracking Collector')
            ->assertJsonPath('user_lat', 28.6139);
    }

    public function test_user_can_scan_waste_with_frontend_detection_data(): void
    {
        Storage::fake('public');

        $user = User::create([
            'name' => 'AI User',
            'phone' => '9000000023',
            'email' => 'aiuser@example.com',
            'password' => Hash::make('secret123'),
            'role' => 'user',
        ]);

        Sanctum::actingAs($user);

        $this->post('/api/scan/waste', [
            'image' => UploadedFile::fake()->create('capture.jpg', 120, 'image/jpeg'),
            'detected_type' => 'glass',
            'confidence' => 88,
        ], [
            'Accept' => 'application/json',
        ])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('category', 'glass')
            ->assertJsonPath('pickup_waste_type', 'recyclable');
    }

    public function test_user_can_view_premium_plans_and_activate_subscription(): void
    {
        $user = User::create([
            'name' => 'Premium User',
            'phone' => '9000000091',
            'email' => 'premium@example.com',
            'password' => Hash::make('secret123'),
            'role' => 'user',
        ]);

        Sanctum::actingAs($user);

        $this->getJson('/api/premium/plans')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(2, 'plans')
            ->assertJsonPath('current.is_premium', false);

        $this->postJson('/api/premium/subscribe', [
            'plan' => 'annual',
        ])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('subscription.plan', 'annual')
            ->assertJsonPath('subscription.price_inr', 799);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'is_premium' => true,
            'premium_plan' => 'annual',
        ]);
    }

    public function test_user_can_cancel_premium_subscription(): void
    {
        $user = User::create([
            'name' => 'Cancel Premium User',
            'phone' => '9000000093',
            'email' => 'cancelpremium@example.com',
            'password' => Hash::make('secret123'),
            'role' => 'user',
            'is_premium' => true,
            'premium_plan' => 'monthly',
            'premium_expires_at' => now()->addMonth(),
        ]);

        Sanctum::actingAs($user);

        $this->postJson('/api/premium/cancel')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('subscription.is_premium', false);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'is_premium' => false,
            'premium_plan' => null,
            'premium_expires_at' => null,
        ]);
    }

    public function test_premium_only_reward_requires_active_membership(): void
    {
        $user = User::create([
            'name' => 'Standard User',
            'phone' => '9000000092',
            'email' => 'standard@example.com',
            'password' => Hash::make('secret123'),
            'role' => 'user',
            'total_points' => 1200,
        ]);

        $reward = Reward::create([
            'title' => 'Premium Eco Hamper',
            'category' => 'premium_hamper',
            'points_required' => 400,
            'stock' => 5,
            'is_active' => true,
            'is_premium_only' => true,
        ]);

        Sanctum::actingAs($user);

        $this->getJson('/api/rewards')
            ->assertOk()
            ->assertJsonCount(1, 'rewards')
            ->assertJsonPath('rewards.0.is_locked', true)
            ->assertJsonPath('rewards.0.lock_reason', 'Premium required');

        $this->postJson("/api/rewards/{$reward->id}/redeem")
            ->assertStatus(403)
            ->assertJsonPath('message', 'This reward is for Premium members only.');

        $this->postJson('/api/premium/subscribe', [
            'plan' => 'monthly',
        ])->assertOk();

        $this->getJson('/api/rewards')
            ->assertOk()
            ->assertJsonCount(1, 'rewards')
            ->assertJsonPath('rewards.0.can_redeem', true)
            ->assertJsonPath('rewards.0.is_locked', false);

        $this->postJson("/api/rewards/{$reward->id}/redeem")
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('reward.name', 'Premium Eco Hamper');
    }

    public function test_collector_can_manage_assigned_pickup_and_award_points(): void
    {
        $user = User::create([
            'name' => 'Citizen',
            'phone' => '9000000003',
            'email' => 'citizen@example.com',
            'password' => Hash::make('secret123'),
            'role' => 'user',
        ]);

        $collectorUser = User::create([
            'name' => 'Collector User',
            'phone' => '9000000004',
            'email' => 'collector@example.com',
            'password' => Hash::make('secret123'),
            'role' => 'collector',
        ]);

        $collector = WasteCollector::create([
            'user_id' => $collectorUser->id,
            'name' => 'Collector One',
            'phone' => '9000000004',
            'qr_code' => 'TDC-123456',
            'zone' => 'Zone A',
            'is_active' => true,
            'is_available' => true,
        ]);

        $pickup = Pickup::create([
            'user_id' => $user->id,
            'collector_id' => $collector->id,
            'address' => 'Assigned Address',
            'waste_type' => 'organic',
            'status' => 'assigned',
            'estimated_weight_kg' => 2,
            'scheduled_at' => now()->subMinute(),
            'assigned_at' => now()->subMinutes(10),
            'qr_token' => 'PICKUP12345',
        ]);

        Sanctum::actingAs($collectorUser);

        $this->getJson('/api/assigned-pickups')
            ->assertOk()
            ->assertJsonCount(1, 'pickups');

        $this->patchJson("/api/pickups/{$pickup->id}/complete")
            ->assertStatus(422)
            ->assertJsonPath('message', 'Scan the pickup QR before completing this pickup.');

        $this->postJson('/api/scan/collector-qr', [
            'pickup_id' => $pickup->id,
            'qr_token' => 'WRONGTOKEN',
        ])
            ->assertStatus(422)
            ->assertJsonPath('message', 'Invalid QR token. Please scan the correct pickup code.');

        $this->postJson('/api/scan/collector-qr', [
            'pickup_id' => $pickup->id,
            'qr_token' => 'PICKUP12345',
        ])
            ->assertOk()
            ->assertJsonPath('verified', true);

        $this->patchJson("/api/pickups/{$pickup->id}/start")
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('pickup.status', 'in_progress');

        $this->patchJson("/api/pickups/{$pickup->id}/complete")
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('points_earned', 20);

        $this->assertDatabaseHas('point_transactions', [
            'pickup_id' => $pickup->id,
            'type' => 'earned_pickup',
            'points' => 20,
        ]);
    }

    public function test_user_can_scan_waste_and_verify_collector_qr(): void
    {
        Storage::fake('public');

        $user = User::create([
            'name' => 'Scanner',
            'phone' => '9000000005',
            'email' => 'scanner@example.com',
            'password' => Hash::make('secret123'),
            'role' => 'user',
        ]);

        $collectorUser = User::create([
            'name' => 'QR Collector',
            'phone' => '9000000006',
            'email' => 'qrcollector@example.com',
            'password' => Hash::make('secret123'),
            'role' => 'collector',
        ]);

        WasteCollector::create([
            'user_id' => $collectorUser->id,
            'name' => 'QR Collector',
            'phone' => '9000000006',
            'qr_code' => 'TDC-QR-001',
            'zone' => 'Zone B',
            'is_active' => true,
        ]);

        Sanctum::actingAs($user);

        $this->post('/api/scan/waste', [
            'image' => UploadedFile::fake()->create('plastic-bottle.jpg', 100, 'image/jpeg'),
        ], [
            'Accept' => 'application/json',
        ])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('category', 'plastic');

        $this->postJson('/api/scan/collector-qr', [
            'qr_code' => 'TDC-QR-001',
        ])
            ->assertOk()
            ->assertJsonPath('verified', true);
    }

    public function test_collector_cannot_start_pickup_before_scheduled_time(): void
    {
        $collectorUser = User::create([
            'name' => 'Early Collector',
            'phone' => '9000000012',
            'email' => 'earlycollector@example.com',
            'password' => Hash::make('secret123'),
            'role' => 'collector',
        ]);

        $collector = WasteCollector::create([
            'user_id' => $collectorUser->id,
            'name' => 'Early Collector',
            'phone' => '9000000012',
            'qr_code' => 'TDC-EARLY-001',
            'zone' => 'Zone D',
            'is_active' => true,
            'is_available' => true,
        ]);

        $user = User::create([
            'name' => 'Future Pickup User',
            'phone' => '9000000013',
            'email' => 'futurepickup@example.com',
            'password' => Hash::make('secret123'),
            'role' => 'user',
        ]);

        $pickup = Pickup::create([
            'user_id' => $user->id,
            'collector_id' => $collector->id,
            'address' => 'Future Address',
            'waste_type' => 'mixed',
            'status' => 'assigned',
            'scheduled_at' => now()->addHour(),
            'qr_token' => 'FUTURETOKEN',
        ]);

        Sanctum::actingAs($collectorUser);

        $this->patchJson("/api/pickups/{$pickup->id}/start")
            ->assertStatus(422)
            ->assertJsonPath('message', 'Pickup can only be completed after scheduled time');
    }

    public function test_collector_can_update_live_location(): void
    {
        $collectorUser = User::create([
            'name' => 'Moving Collector',
            'phone' => '9000000024',
            'email' => 'movingcollector@example.com',
            'password' => Hash::make('secret123'),
            'role' => 'collector',
        ]);

        WasteCollector::create([
            'user_id' => $collectorUser->id,
            'name' => 'Moving Collector',
            'phone' => '9000000024',
            'qr_code' => 'TDC-MOVE-001',
            'zone' => 'Zone M',
            'is_active' => true,
            'is_available' => true,
        ]);

        Sanctum::actingAs($collectorUser);

        $this->postJson('/api/collector/location', [
            'latitude' => 28.6111,
            'longitude' => 77.2211,
        ])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('location.latitude', 28.6111);
    }

    public function test_admin_can_create_collector_and_view_dashboard_data(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'phone' => '9000000007',
            'email' => 'admin@example.com',
            'password' => Hash::make('secret123'),
            'role' => 'admin',
        ]);

        Sanctum::actingAs($admin);

        $this->postJson('/api/admin/collectors', [
            'name' => 'New Collector',
            'phone' => '9000000008',
            'zone' => 'Zone C',
            'vehicle_type' => 'bike',
        ])
            ->assertCreated()
            ->assertJsonPath('success', true);

        $this->getJson('/api/admin/dashboard')
            ->assertOk()
            ->assertJsonPath('success', true);
    }
}
