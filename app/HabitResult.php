<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HabitResult extends Model
{
    protected $fillable = ['year','month','date','result','created_at','updated_at'];
}