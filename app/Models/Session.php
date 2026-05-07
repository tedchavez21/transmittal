<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $table = 'active_sessions';

    protected $fillable = [
        'user_name',
        'channel',
        'session_id',
        'last_activity',
        'is_away',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'last_activity' => 'datetime',
        'is_away' => 'boolean',
    ];

    /**
     * Scope to get active sessions (last activity within 5 minutes)
     */
    public function scopeActive($query)
    {
        return $query->where('last_activity', '>=', now()->subMinutes(5));
    }

    /**
     * Scope to get sessions by channel
     */
    public function scopeByChannel($query, $channel)
    {
        return $query->where('channel', $channel);
    }
}
