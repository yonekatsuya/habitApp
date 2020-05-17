<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\HabitPost;

class HabitController extends Controller
{
    public function habitPost(Request $request) {
        $habitPost = new HabitPost();
        $habitPost->year = $request->year;
        $habitPost->month = $request->month;
        $habitPost->item = $request->habitText;
        $habitPost->save();
        $habitPosts = HabitPost::where('month',5)->get();
        return response()->json($habitPosts);
    }

    public function habitGet() {
        $habitPosts = HabitPost::get();
        return response()->json($habitPosts);
    }

    public function habitDelete(Request $request) {
        $id = $request->id;
        HabitPost::where('id',$id)->delete();
        $habitPosts = HabitPost::where('month',5)->get();
        return response()->json($habitPosts);
    }
}
