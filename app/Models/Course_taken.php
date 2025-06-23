<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course_taken extends Model
{
     protected $fillable = [
        'status',
        'grade',
        'semester_coursed'
    ];
    
    protected $table = 'courses_taken';
}
