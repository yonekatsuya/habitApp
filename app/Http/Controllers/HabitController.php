<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// 習慣項目を管理
use App\HabitPost;
use App\HabitResult;
use App\HabitCheckResult;
// 習慣項目の目標達成指標を管理
use App\HabitAchiveRate;

use Log;

class HabitController extends Controller
{
    // 該当年月の習慣項目を登録する（habit_postsテーブル）
    public function habitPost(Request $request) {
        // 習慣登録モーダルから送られてきたデータを保存する
        $habitPost = new HabitPost();
        $habitPost->user_id = $request->id;
        $habitPost->year = $request->year;
        $habitPost->month = $request->month;
        $habitPost->item = $request->habitText;
        $habitPost->save();

        // 上記で登録した習慣項目データを取得する
        $habitPost = HabitPost::where('user_id',$request->id)->orderBy('id','desc')->first();
        $id = $habitPost->id;

        // 習慣項目の目標達成指標を登録する（habit_achive_ratesテーブル）
        // デフォルト値を「0」とする
        $habitAchiveRate = new HabitAchiveRate();
        $habitAchiveRate->user_id = $request->id;
        $habitAchiveRate->habit_post_id = $id;
        $habitAchiveRate->achive_rate = 0;
        $habitAchiveRate->save();

        // 習慣項目登録後、最新のデータを取得する（habit_postsテーブル）
        $habitPosts = HabitPost::where('user_id',$request->id)->where('year',$request->year)->where('month',$request->month)->get();
        return response()->json($habitPosts);
    }

    // 該当年月の習慣項目を取得する（habit_postsテーブル）
    public function habitGet(Request $request) {
        $habitPosts = HabitPost::where('user_id',$request->loginId)->where('year',$request->year)->where('month',$request->month)->get();
        return response()->json($habitPosts);
    }

    // 該当年月の習慣項目と、それに紐付く目標達成指標を取得する
    public function habitGetItemAndAchiveRate(Request $request) {
        // 該当年月の習慣項目を取得する（habit_postsテーブル）
        $habitPosts = HabitPost::where('year',$request->year)->where('month',$request->month)->get();
        // 各習慣項目に対応する目標達成指標を取得する（リレーション）
        $array = [];
        for ($i = 0;$i < count($habitPosts);$i++) {
            $array[] = $habitPosts[$i]->habitAchiveRate->achive_rate;
        }
        return response()->json([$habitPosts, $array]);
    }

    // 習慣項目を削除する
    public function habitDelete(Request $request) {
        $id = $request->id;
        $user_id = $request->loginId;
        HabitPost::where('user_id',$user_id)->where('id',$id)->delete();
        // 削除後、最新の該当年月の習慣項目を取得する（habit_postsテーブル）
        $habitPosts = HabitPost::where('user_id',$user_id)->where('year',$request->year)->where('month',$request->month)->get();
        return response()->json($habitPosts);
    }

    // 習慣チェックの評価結果を登録する
    public function habitResult(Request $request) {
        // 習慣項目数を取得
        $itemCount = count($request['result']);
        $resultCount = [];
        // 該当月の習慣項目数だけループを回す
        for ($i = 0;$i < $itemCount;$i++) {
            $array = explode("/",$request['result'][$i]);
            // 習慣チェックの中で「◎」がいくつあるかを確認
            if ($array[3] == '◎') {
                $resultCount[] = 1;
            }

            // 各習慣項目評価の結果をhabit_check_resultsテーブルに格納する
            $habitCheckResult = new HabitCheckResult();
            $habitCheckResult->year = $array[0];
            $habitCheckResult->month = $array[1];
            $habitCheckResult->date = $array[2];
            $habitCheckResult->result = $array[3];
            $habitCheckResult->habit_post_id = $array[4];
            $habitCheckResult->save();
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
        // 「◎」が一つ以上あれば目標達成率を計算し、一つもなければ「0」を格納する
        if (count($resultCount) >= 1) {
            $result = floor((count($resultCount) / $itemCount) * 100);
            $habitResult->result = $result;
        } else {
            $habitResult->result = 0;
        }
        $habitResult->save();
    }

    // 習慣目標達成率を取得する（habit_resultsテーブル）
    public function habitResultGet(Request $request) {
        $year = $request->year;
        $month = $request->month;
        $date = $request->date;
        // 該当年月日のデータが存在する場合のみ、目標達成率を取得する
        if (HabitResult::where('year',$year)->where('month',$month)->where('date',$date)->exists()) {
            $result = HabitResult::where('year',$year)->where('month',$month)->where('date',$date)->get(['result']);
        } else {
            $result = null;
        }
        return response()->json($result);
    }

    // 該当年月の習慣チェック評価結果を取得する（habit_check_resultsテーブル）
    public function habitGetDateResult(Request $request) {
        $habitCheckResult = HabitCheckResult::where('year',$request->year)->where('month',$request->month)->get(['habit_post_id','date','result']);
        return response()->json($habitCheckResult);
    }

    // サイドバー上部に表示する今月の目標達成率を取得する
    public function habitGetMonthAchiveRate(Request $request) {
        $habitResults = HabitResult::where('year',$request->year)->where('month',$request->month)->get(['result']);
        $array = [];
        for ($i = 0;$i < count($habitResults);$i++) {
            $array[] = $habitResults[$i]['result'];
        }
        $max = count($array) * 100;
        $num = 0;
        for ($i = 0;$i < count($array);$i++) {
            $num += $array[$i];
        }

        // 目標達成率の計算
        $response = ($num / $max) * 100;
        return response()->json($response);
    }

}
