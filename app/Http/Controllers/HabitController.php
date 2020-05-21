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
        // 習慣項目数を取得
        $itemCount = count($request['result']);
        $resultCount = [];
        // 習慣チェックの中で「verygood」がいくつあるかを確認
        for ($i = 0;$i < $itemCount;$i++) {
            $array = explode("/",$request['result'][$i]);
            if ($array[3] == 'verygood') {
                $resultCount[] = 1;
            }
        }
        $habitResult = new HabitResult();
        // とりあえず一番最初の習慣項目チェックデータを配列にして、年月日を登録できるよう準備
        $array = explode("/",$request['result'][0]);
        // もう既に同じ年月日のデータがhabit_resultsテーブルに存在すれば、削除する
        if (HabitResult::where('year',$array[0])->where('month',$array[1])->where('date',$array[2])->exists()) {
            HabitResult::where('year',$array[0])->where('month',$array[1])->where('date',$array[2])->delete();
        }
        $habitResult->year = $array[0];
        $habitResult->month = $array[1];
        $habitResult->date = $array[2];
        // 「verygood」が一つ以上あれば目標達成率を計算し、一つもなければ「0」を格納する
        if (count($resultCount) >= 1) {
            $result = floor((count($resultCount) / $itemCount) * 100);
            $habitResult->result = $result;
        } else {
            $habitResult->result = 0;
        }
        $habitResult->save();
    }

    public function habitResultGet(Request $request) {
        $year = $request->year;
        $month = $request->month;
        $date = $request->date;
        if (HabitResult::where('year',$year)->where('month',$month)->where('date',$date)->exists()) {
            $result = HabitResult::where('year',$year)->where('month',$month)->where('date',$date)->get(['result']);
        } else {
            $result = null;
        }
        return response()->json($result);
    }
}
