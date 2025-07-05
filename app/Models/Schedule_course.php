<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule_Course extends Model
{
    protected $fillable = [
        'course_code',
        'name',
        'credits',
        'group',
        'schedule_list',
        'format',
        'classroom',
        'requirements',
        'corequisites'
    ];

    protected $table = 'schedule_courses';
}
