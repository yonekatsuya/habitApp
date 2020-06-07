<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HabitResult extends Model
{
    protected $fillable = ['user_id','year','month','date','result','created_at','updated_at'];
}