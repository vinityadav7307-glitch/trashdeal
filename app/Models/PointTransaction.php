<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PointTransaction extends Model
{
    protected $fillable = [
        'user_id', 'pickup_id', 'reward_id', 'type',
        'points', 'balance_after', 'description',
        'reference_code', 'status',
    ];

    public function user()   { return $this->belongsTo(User::class); }
    public function pickup() { return $this->belongsTo(Pickup::class); }
    public function reward() { return $this->belongsTo(Reward::class); }
}
