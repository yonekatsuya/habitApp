<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\HabitPost;
use Log;

class HabitController extends Controller
{
    public function habitPost() {
        $habitPost = new HabitPost();
        $habitPost->year = 2020;
        $habitPost->month = 05;
        $habitPost->item = '毎朝6時に起きる';
        $habitPost->save();
    }

    public function habitGet() {
        Log::debug('test');
        $habitPosts = HabitPost::get();
        return response()->json($habitPosts);
    }
}
