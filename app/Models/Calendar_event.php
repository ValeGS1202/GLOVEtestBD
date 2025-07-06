<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calendar_event extends Model
{
     protected $fillable = [
        'title',
        'start_date',
        'end_date',
    ];

    protected $table = 'calendar_events';
}
