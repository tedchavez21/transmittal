<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Officer extends Model
{
    protected $fillable = [
        'name',
        'username',
        'password',
        'active',
        'approved',
        'approved_at',
    ];

    protected $casts = [
        'active' => 'boolean',
        'approved' => 'boolean',
        'approved_at' => 'datetime',
    ];

    protected $hidden = [
        'password',
    ];
}
