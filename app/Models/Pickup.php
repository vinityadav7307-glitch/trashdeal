<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pickup extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'collector_id', 'address', 'pickup_lat', 'pickup_lng',
        'waste_type', 'status', 'qr_token', 'estimated_weight_kg', 'actual_weight_kg',
        'points_earned', 'notes', 'pickup_photo', 'delivery_photo',
        'scheduled_at', 'assigned_at', 'picked_at', 'qr_verified_at', 'verified_at', 'completed_at',
    ];

    protected $casts = [
        'pickup_lat'    => 'float',
        'pickup_lng'    => 'float',
        'estimated_weight_kg' => 'float',
        'actual_weight_kg' => 'float',
        'scheduled_at' => 'datetime',
        'assigned_at'  => 'datetime',
        'picked_at'    => 'datetime',
        'qr_verified_at' => 'datetime',
        'verified_at'  => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user()      { return $this->belongsTo(User::class); }
    public function collector() { return $this->belongsTo(WasteCollector::class, 'collector_id'); }
    public function scans()     { return $this->hasMany(WasteScan::class); }
}
