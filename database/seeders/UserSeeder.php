<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

DB::table('users')->insert([
    'name' => 'Demo User',
    'email' => 'user@trashdeal.demo',
    'phone' => '9000010002',
    'password' => Hash::make('secret123'),
    'created_at' => now(),
    'updated_at' => now(),
]);
