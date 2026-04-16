<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    protected $fillable = [
        'farmerName',
        'address',
        'province',
        'municipality',
        'barangay',
        'line',
        'program',
        'causeOfDamage',
        'modeOfPayment',
        'remarks',
        'encoderName',
        'approved',
        'approved_at',
    ];

    protected $casts = [
        'approved' => 'boolean',
        'approved_at' => 'datetime',
    ];
}
