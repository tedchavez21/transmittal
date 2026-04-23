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
        'accounts',
        'facebook_page_url',
        'date_occurrence',
        'remarks',
        'source',
        'transmittal_number',
        'admin_transmittal_number',
        'admin_transmittal_assigned_at',
        'encoderName',
        'approved',
        'approved_at',
    ];

    protected $casts = [
        'approved' => 'boolean',
        'approved_at' => 'datetime',
    ];
}
