<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\HabitPost;
use App\HabitResult;
use App\HabitCheckResult;
use App\HabitAchiveRate;
use App\MonthPurposeManage;
use App\MonthAchiveManage;
use App\MonthGoalImageManage;
use App\MonthPassionManage;
use Log;

class HabitController extends Controller
{
    // 該当年月の習慣項目を登録する（habit_postsテーブル）
    public function habitPost(Request $request) {
        $habitPost = new HabitPost();
        $habitPost->year = $request->year;
        $habitPost->month = $request->month;
        $habitPost->item = $request->habitText;
        $habitPost->save();

        $habitPost = HabitPost::orderBy('id','desc')->first();
        $id = $habitPost->id;

        // 習慣項目の目標達成指標を登録する（habit_achive_ratesテーブル）
        $habitAchiveRate = new HabitAchiveRate();
        $habitAchiveRate->habit_post_id = $id;
        $habitAchiveRate->achive_rate = 0;
        $habitAchiveRate->save();

        // 登録後、該当年月の最新の習慣項目を取得する（habit_postsテーブル）
        $habitPosts = HabitPost::where('year',$request->year)->where('month',$request->month)->get();
        return response()->json($habitPosts);
    }

    public function habitGet(Request $request) {
        // 該当年月の習慣項目を取得する（habit_postsテーブル）
        $habitPosts = HabitPost::where('year',$request->year)->where('month',$request->month)->get();
        return response()->json($habitPosts);
    }

    public function habitGetItemAndAchiveRate(Request $request) {
        // 該当年月の習慣項目を取得する（habit_postsテーブル）
        $habitPosts = HabitPost::where('year',$request->year)->where('month',$request->month)->get();
        $array = [];
        for ($i = 0;$i < count($habitPosts);$i++) {
            $array[] = $habitPosts[$i]->habitAchiveRate->achive_rate;
        }
        return response()->json([$habitPosts, $array]);
    }

    // 習慣項目を削除する
    public function habitDelete(Request $request) {
        $id = $request->id;
        HabitPost::where('id',$id)->delete();
        // 削除後、最新の該当年月の習慣項目を取得する（habit_postsテーブル）
        $habitPosts = HabitPost::where('year',$request->year)->where('month',$request->month)->get();
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

    public function getHabitAchiveRate(Request $request) {
        $array = [];
        for ($i = 0;$i < count($request['result']);$i++) {
            $achive_rate = HabitAchiveRate::where('habit_post_id',$request['result'][$i])->first()->achive_rate;
            $array[] = $achive_rate;
        }
        return response()->json($array);
    }

    public function registerAchiveRate(Request $request) {
        $habitAchiveRate = HabitAchiveRate::where('habit_post_id',$request->id)->first();
        $habitAchiveRate->achive_rate = $request->value;
        $habitAchiveRate->save();
    }

    public function purposeRegister(Request $request) {
        $monthPurposeManage = new MonthPurposeManage();
        $monthPurposeManage->year = $request['year'];
        $monthPurposeManage->month = $request['month'];
        $monthPurposeManage->purpose = $request['purposeText'];
        $monthPurposeManage->save();
    }

    public function achiveRegister(Request $request) {
        $monthAchiveManage = new MonthAchiveManage();
        $monthAchiveManage->year = $request['year'];
        $monthAchiveManage->month = $request['month'];
        $monthAchiveManage->achive = $request['achiveText'];
        $monthAchiveManage->save();
    }

    public function goalImageRegister(Request $request) {
        $monthGoalImageManage = new MonthGoalImageManage();
        $monthGoalImageManage->year = $request['year'];
        $monthGoalImageManage->month = $request['month'];
        $monthGoalImageManage->goal_image = $request['goalImageText'];
        $monthGoalImageManage->save();
    }

    public function passionRegister(Request $request) {
        $monthPassionManage = new MonthPassionManage();
        $monthPassionManage->year = $request['year'];
        $monthPassionManage->month = $request['month'];
        $monthPassionManage->passion = $request['passionText'];
        $monthPassionManage->save();
    }

    public function confirmExistsAchiveData(Request $request) {
        $year = $request['year'];
        $month = $request['month'];
        $monthPurposeManage = MonthPurposeManage::first();
        $monthAchiveManage = MonthAchiveManage::first();
        $monthGoalImageManage = MonthGoalImageManage::first();
        $monthPassionManage = MonthPassionManage::first();

        $response = [
            $monthPurposeManage, $monthAchiveManage, $monthGoalImageManage, $monthPassionManage
        ];
        return response()->json($response);
    }

    public function purposeEdit(Request $request) {
        $purposeText = $request['purposeText'];
        $year = $request['year'];
        $month = $request['month'];
        if (MonthPurposeManage::where('year',$year)->where('month',$month)->exists()) {
            MonthPurposeManage::where('year',$year)->where('month',$month)->delete();
        }
        $monthPurposeManage = new MonthPurposeManage();
        $monthPurposeManage->year = $year;
        $monthPurposeManage->month = $month;
        $monthPurposeManage->purpose = $purposeText;
        $monthPurposeManage->save();
    }

    public function achiveEdit(Request $request) {
        $achiveText = $request['achiveText'];
        $year = $request['year'];
        $month = $request['month'];
        if (MonthAchiveManage::where('year',$year)->where('month',$month)->exists()) {
            MonthAchiveManage::where('year',$year)->where('month',$month)->delete();
        }
        $monthAchiveManage = new MonthAchiveManage();
        $monthAchiveManage->year = $year;
        $monthAchiveManage->month = $month;
        $monthAchiveManage->achive = $achiveText;
        $monthAchiveManage->save();
    }

    public function goalImageEdit(Request $request) {
        $goalImageText = $request['goalImageText'];
        $year = $request['year'];
        $month = $request['month'];
        if (MonthGoalImageManage::where('year',$year)->where('month',$month)->exists()) {
            MonthGoalImageManage::where('year',$year)->where('month',$month)->delete();
        }
        $monthGoalImageManage = new MonthGoalImageManage();
        $monthGoalImageManage->year = $year;
        $monthGoalImageManage->month = $month;
        $monthGoalImageManage->goal_image = $goalImageText;
        $monthGoalImageManage->save();
    }

    public function passionEdit(Request $request) {
        $passionText = $request['passionText'];
        $year = $request['year'];
        $month = $request['month'];
        if (MonthPassionManage::where('year',$year)->where('month',$month)->exists()) {
            MonthPassionManage::where('year',$year)->where('month',$month)->delete();
        }
        $monthPassionManage = new MonthPassionManage();
        $monthPassionManage->year = $year;
        $monthPassionManage->month = $month;
        $monthPassionManage->passion = $passionText;
        $monthPassionManage->save();
    }

}
