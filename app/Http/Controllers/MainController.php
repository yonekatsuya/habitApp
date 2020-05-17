<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\HabitPost;

class MainController extends Controller
{
    public function index() {
        $habitPosts = HabitPost::get();
        $param = [
            'habitPosts' => $habitPosts
        ];
        return view('main.index',$param);
    }
}
