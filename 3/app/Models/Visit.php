<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends  Model
{
   protected $fillable = [
        'ip',
        'city',
        'visit_time',
    ];
    public $timestamps = true;
    protected $table = 'visits';
}
