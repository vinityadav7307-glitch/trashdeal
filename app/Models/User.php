<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'phone', 'email', 'password', 'role',
        'total_points', 'is_premium', 'premium_plan',
        'premium_expires_at', 'address', 'zone', 'latitude', 'longitude',
        'profile_photo', 'otp_code', 'otp_expires_at', 'is_verified',
    ];

    protected $hidden = ['password', 'remember_token', 'otp_code'];

    protected $casts = [
        'is_premium'         => 'boolean',
        'is_verified'        => 'boolean',
        'latitude'           => 'float',
        'longitude'          => 'float',
        'premium_expires_at' => 'datetime',
        'otp_expires_at'     => 'datetime',
    ];

    public function pickups()      { return $this->hasMany(Pickup::class); }
    public function transactions() { return $this->hasMany(PointTransaction::class); }
    public function scans()        { return $this->hasMany(WasteScan::class); }
    public function notifications(){ return $this->hasMany(Notification::class); }
    public function collector()    { return $this->hasOne(WasteCollector::class); }
}
