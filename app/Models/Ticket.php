<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'customer', 'topic', 'priority', 'status', 'l2',
        'date_arrived', 'time_arrived', 'date_answered', 'time_answered',
        'assigned_to_me'
    ];

    protected $casts = [
        'l2' => 'boolean',
        'assigned_to_me' => 'boolean',
        'date_arrived' => 'date:Y-m-d', // Cast to date-only string
        'time_arrived' => 'datetime:H:i:s', // Cast to time-only string
        'date_answered' => 'date:Y-m-d', // Cast to date-only string
        'time_answered' => 'datetime:H:i:s', // Cast to time-only string
    ];
}
