<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\HabitPost;
use App\HabitResult;
use Log;

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

    public function habitResult(Request $request) {
        for ($i = 0;$i < count($request['result']);$i++) {
            $array = explode("/",$request['result'][$i]);
            $habitResult = new habitResult();
            $habitResult->year = $array[0];
            $habitResult->month = $array[1];
            $habitResult->date = $array[2];
            $habitResult->result = $array[3];
            $habitResult->save();
        }
    }
}
