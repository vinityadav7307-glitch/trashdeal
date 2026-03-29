<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtpVerification extends Model
{
    protected $fillable = [
        'phone', 'otp_code', 'purpose',
        'is_used', 'attempts', 'expires_at',
    ];

    protected $casts = [
        'is_used'    => 'boolean',
        'expires_at' => 'datetime',
    ];
}
