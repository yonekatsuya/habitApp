<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HabitPost extends Model
{
    protected $fillable = ['year','month','item','created_at','updated_at'];

    public function habitCheckResults() {
        return $this->hasMany('App\HabitCheckResult');
    }
}
