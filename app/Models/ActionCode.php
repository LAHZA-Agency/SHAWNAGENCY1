<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActionCode extends Model
{
    protected $fillable = [
        'code',
        'action',
        'data',
        'user_id',
        'expires_at'
    ];

    protected $casts = [
        'data' => 'array',
        'expires_at' => 'datetime'
    ];
}
