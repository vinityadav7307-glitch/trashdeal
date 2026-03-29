<?php

namespace Database\Seeders;

use App\Models\Pickup;
use App\Models\PointTransaction;
use App\Models\Reward;
use App\Models\User;
use App\Models\WasteCollector;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $collectorUser = User::updateOrCreate(
            ['phone' => '9000010001'],
            [
                'name' => 'Ravi Collector',
                'email' => 'collector@trashdeal.demo',
                'password' => Hash::make('secret123'),
                'role' => 'collector',
                'address' => 'Sector 18, Noida',
                'latitude' => 28.5708,
                'longitude' => 77.3260,
                'total_points' => 0,
            ]
        );

        $collector = WasteCollector::updateOrCreate(
            ['user_id' => $collectorUser->id],
            [
                'name' => 'Ravi Collector',
                'phone' => '9000010001',
                'qr_code' => 'TDC-DEMO-COLLECTOR',
                'zone' => 'Noida Central',
                'vehicle_type' => 'E-rickshaw',
                'vehicle_number' => 'UP16TD1001',
                'is_active' => true,
                'is_available' => false,
                'current_lat' => 28.5712,
                'current_lng' => 77.3255,
                'latitude' => 28.5708,
                'longitude' => 77.3260,
                'total_pickups' => 14,
                'rating' => 4.8,
            ]
        );

        $user = User::updateOrCreate(
            ['phone' => '9000010002'],
            [
                'name' => 'Ananya Sharma',
                'email' => 'user@trashdeal.demo',
                'password' => Hash::make('secret123'),
                'role' => 'user',
                'address' => 'Sector 62, Noida',
                'latitude' => 28.6280,
                'longitude' => 77.3649,
                'total_points' => 540,
            ]
        );

        $premiumUser = User::updateOrCreate(
            ['phone' => '9000010003'],
            [
                'name' => 'Karan Mehta',
                'email' => 'premium@trashdeal.demo',
                'password' => Hash::make('secret123'),
                'role' => 'user',
                'address' => 'Indirapuram, Ghaziabad',
                'latitude' => 28.6469,
                'longitude' => 77.3697,
                'total_points' => 960,
                'is_premium' => true,
                'premium_plan' => 'annual',
                'premium_expires_at' => now()->addMonths(8),
            ]
        );

        $completedPickup = Pickup::updateOrCreate(
            ['user_id' => $user->id, 'notes' => 'demo-completed-pickup'],
            [
                'collector_id' => $collector->id,
                'address' => 'Sector 62, Noida',
                'pickup_lat' => 28.6280,
                'pickup_lng' => 77.3649,
                'waste_type' => 'recyclable',
                'status' => 'completed',
                'qr_token' => 'DEMOQR001',
                'estimated_weight_kg' => 4.0,
                'actual_weight_kg' => 4.0,
                'points_earned' => 40,
                'scheduled_at' => now()->subDays(2)->setTime(10, 30),
                'assigned_at' => now()->subDays(2)->setTime(10, 0),
                'picked_at' => now()->subDays(2)->setTime(10, 45),
                'qr_verified_at' => now()->subDays(2)->setTime(10, 40),
                'verified_at' => now()->subDays(2)->setTime(10, 50),
                'completed_at' => now()->subDays(2)->setTime(10, 50),
            ]
        );

        $assignedPickup = Pickup::updateOrCreate(
            ['user_id' => $user->id, 'notes' => 'demo-assigned-pickup'],
            [
                'collector_id' => $collector->id,
                'address' => 'Sector 62, Noida',
                'pickup_lat' => 28.6280,
                'pickup_lng' => 77.3649,
                'waste_type' => 'organic',
                'status' => 'assigned',
                'qr_token' => 'DEMOQR002',
                'estimated_weight_kg' => 3.5,
                'points_earned' => 0,
                'scheduled_at' => now()->addHour(),
                'assigned_at' => now()->subMinutes(20),
            ]
        );

        $progressPickup = Pickup::updateOrCreate(
            ['user_id' => $premiumUser->id, 'notes' => 'demo-progress-pickup'],
            [
                'collector_id' => $collector->id,
                'address' => 'Indirapuram, Ghaziabad',
                'pickup_lat' => 28.6469,
                'pickup_lng' => 77.3697,
                'waste_type' => 'mixed',
                'status' => 'in_progress',
                'qr_token' => 'DEMOQR003',
                'estimated_weight_kg' => 5.0,
                'points_earned' => 0,
                'scheduled_at' => now()->subMinutes(15),
                'assigned_at' => now()->subHour(),
                'picked_at' => now()->subMinutes(10),
                'qr_verified_at' => now()->subMinutes(8),
            ]
        );

        PointTransaction::updateOrCreate(
            ['user_id' => $user->id, 'pickup_id' => $completedPickup->id, 'type' => 'earned_pickup'],
            [
                'points' => 40,
                'balance_after' => 640,
                'description' => 'Demo pickup completed and points awarded.',
                'status' => 'completed',
            ]
        );

        $reward = Reward::where('title', 'Rice 5kg Pack')->first();

        if ($reward) {
            PointTransaction::updateOrCreate(
                ['user_id' => $user->id, 'reward_id' => $reward->id, 'type' => 'redeemed'],
                [
                    'points' => -100,
                    'balance_after' => 540,
                    'description' => 'Demo reward redeemed successfully.',
                    'reference_code' => 'TD-DEMO-1001',
                    'status' => 'completed',
                ]
            );
        }

        DB::table('notifications')->updateOrInsert(
            ['user_id' => $user->id, 'title' => 'Collector assigned'],
            [
                'body' => 'Ravi Collector is assigned for your next pickup.',
                'type' => 'pickup_assigned',
                'data' => json_encode(['pickup_id' => $assignedPickup->id]),
                'is_read' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('notifications')->updateOrInsert(
            ['user_id' => $user->id, 'title' => 'Pickup completed'],
            [
                'body' => 'Your last pickup was completed and points were added.',
                'type' => 'pickup_completed',
                'data' => json_encode(['pickup_id' => $completedPickup->id, 'points' => 40]),
                'is_read' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('notifications')->updateOrInsert(
            ['user_id' => $premiumUser->id, 'title' => 'Collector on the way'],
            [
                'body' => 'Your premium pickup is in progress right now.',
                'type' => 'pickup_assigned',
                'data' => json_encode(['pickup_id' => $progressPickup->id]),
                'is_read' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
