<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reward extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'description', 'category', 'brand',
        'points_required', 'stock',
        'is_active', 'is_premium_only', 'image_path', 'expires_at',
    ];

    protected $casts = [
        'is_active'       => 'boolean',
        'is_premium_only' => 'boolean',
        'expires_at'      => 'datetime',
    ];
}
