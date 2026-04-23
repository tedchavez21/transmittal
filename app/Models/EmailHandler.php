<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailHandler extends Model
{
    protected $fillable = [
        'name',
        'approved',
        'approved_at',
        'active',
    ];

    protected $casts = [
        'approved' => 'boolean',
        'approved_at' => 'datetime',
        'active' => 'boolean',
    ];
}
