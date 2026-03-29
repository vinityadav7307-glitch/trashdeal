<?php

namespace Database\Seeders;

use App\Models\Reward;
use Illuminate\Database\Seeder;

class RewardSeeder extends Seeder
{
    public function run(): void
    {
        $rewards = [
            [
                'title' => 'Rice 5kg Pack',
                'description' => 'Essential grocery reward for home use.',
                'category' => 'grocery',
                'points_required' => 300,
                'stock' => 25,
                'is_active' => true,
                'is_premium_only' => false,
            ],
            [
                'title' => 'Fresh Grocery Basket',
                'description' => 'Mixed weekly grocery essentials.',
                'category' => 'grocery',
                'points_required' => 450,
                'stock' => 20,
                'is_active' => true,
                'is_premium_only' => false,
            ],
            [
                'title' => 'Eco Cleaning Kit',
                'description' => 'Reusable eco-friendly cleaning set.',
                'category' => 'eco',
                'points_required' => 350,
                'stock' => 15,
                'is_active' => true,
                'is_premium_only' => false,
            ],
            [
                'title' => 'Tree Plantation Gift',
                'description' => 'Support an eco restoration drive.',
                'category' => 'eco',
                'points_required' => 500,
                'stock' => -1,
                'is_active' => true,
                'is_premium_only' => false,
            ],
            [
                'title' => 'Amazon Voucher',
                'description' => 'Popular shopping voucher reward.',
                'category' => 'voucher',
                'points_required' => 600,
                'stock' => 30,
                'is_active' => true,
                'is_premium_only' => false,
            ],
            [
                'title' => 'Movie Night Voucher',
                'description' => 'Redeem for a movie experience.',
                'category' => 'voucher',
                'points_required' => 800,
                'stock' => 10,
                'is_active' => true,
                'is_premium_only' => true,
            ],
        ];

        foreach ($rewards as $reward) {
            Reward::updateOrCreate(
                ['title' => $reward['title']],
                $reward
            );
        }
    }
}
