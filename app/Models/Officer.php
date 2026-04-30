<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Officer extends Model
{
    protected $fillable = [
        'name',
        'username',
        'password',
        'last_activity',
    ];

    protected $hidden = [
        'password',
    ];
}
