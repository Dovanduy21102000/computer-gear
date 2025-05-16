<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckoutSession extends Model
{
    protected $fillable = [
        'user_id',
        'session_data'
    ];

    protected $casts = [
        'session_data' => 'array'
    ];
}
