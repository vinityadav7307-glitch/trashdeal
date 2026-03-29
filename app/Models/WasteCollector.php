<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WasteCollector extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'name', 'phone', 'qr_code', 'zone',
        'vehicle_type', 'vehicle_number', 'is_active',
        'is_available', 'current_lat', 'current_lng', 'latitude', 'longitude',
        'total_pickups', 'rating',
    ];

    protected $casts = [
        'is_active'    => 'boolean',
        'is_available' => 'boolean',
        'current_lat'  => 'float',
        'current_lng'  => 'float',
        'latitude'     => 'float',
        'longitude'    => 'float',
        'rating'       => 'float',
    ];

    public function user()    { return $this->belongsTo(User::class); }
    public function pickups() { return $this->hasMany(Pickup::class, 'collector_id'); }
}
