<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WasteScan extends Model
{
    protected $fillable = [
        'user_id', 'pickup_id', 'scan_type', 'image_path', 'qr_data',
        'detected_waste', 'waste_category', 'confidence', 'points_awarded',
        'is_verified', 'verified_by', 'ml_raw_response', 'scanned_at',
    ];

    protected $casts = [
        'is_verified'     => 'boolean',
        'ml_raw_response' => 'array',
        'scanned_at'      => 'datetime',
    ];

    public function user()   { return $this->belongsTo(User::class); }
    public function pickup() { return $this->belongsTo(Pickup::class); }
}
