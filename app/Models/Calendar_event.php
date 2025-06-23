<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calendar_event extends Model
{
     protected $fillable = [
        'major_name',
        'title',
        'description',
        'date',
        'time',
        'reminder_date',
        'reminder_time',
        'user_id'
    ];

    protected $table = 'calendar_events';
}
