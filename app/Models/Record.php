<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
     protected $fillable = [
        'user_id'
    ];

    protected $table = 'records';
}
