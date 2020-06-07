<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\HabitAchiveRate;
use App\MonthPurposeManage;
use App\MonthAchiveManage;
use App\MonthGoalImageManage;
use App\MonthPassionManage;

class AchiveManageModalController extends Controller
{
    // 各習慣項目に対応する目標達成指標を取得する
    public function getHabitAchiveRate(Request $request) {
        $array = [];
        for ($i = 0;$i < count($request['result']);$i++) {
            $achive_rate = HabitAchiveRate::where('habit_post_id',$request['result'][$i])->first()->achive_rate;
            $array[] = $achive_rate;
        }
        return response()->json($array);
    }

    // 目標管理モーダルから、各習慣項目に対応する目標達成指標を編集する
    public function registerAchiveRate(Request $request) {
        $habitAchiveRate = HabitAchiveRate::where('habit_post_id',$request->id)->first();
        $habitAchiveRate->achive_rate = $request->value;
        $habitAchiveRate->save();
    }

    // 目標管理モーダルから「目的」を登録する
    public function purposeRegister(Request $request) {
        $monthPurposeManage = new MonthPurposeManage();
        $monthPurposeManage->user_id = $request['loginId'];
        $monthPurposeManage->year = $request['year'];
        $monthPurposeManage->month = $request['month'];
        $monthPurposeManage->purpose = $request['purposeText'];
        $monthPurposeManage->save();
    }

    // 目標管理モーダルから「目標」を登録する
    public function achiveRegister(Request $request) {
        $monthAchiveManage = new MonthAchiveManage();
        $monthAchiveManage->user_id = $request['loginId'];
        $monthAchiveManage->year = $request['year'];
        $monthAchiveManage->month = $request['month'];
        $monthAchiveManage->achive = $request['achiveText'];
        $monthAchiveManage->save();
    }

    // 目標管理モーダルから「ゴールイメージ」を登録する
    public function goalImageRegister(Request $request) {
        $monthGoalImageManage = new MonthGoalImageManage();
        $monthGoalImageManage->user_id = $request['loginId'];
        $monthGoalImageManage->year = $request['year'];
        $monthGoalImageManage->month = $request['month'];
        $monthGoalImageManage->goal_image = $request['goalImageText'];
        $monthGoalImageManage->save();
    }

    // 目標管理モーダルから「意気込み」を登録する
    public function passionRegister(Request $request) {
        $monthPassionManage = new MonthPassionManage();
        $monthPassionManage->user_id = $request['loginId'];
        $monthPassionManage->year = $request['year'];
        $monthPassionManage->month = $request['month'];
        $monthPassionManage->passion = $request['passionText'];
        $monthPassionManage->save();
    }

    // 目標管理モーダルを開いたタイミングで、該当月の目的・目標・ゴールイメージ・意気込みを取得する
    public function confirmExistsAchiveData(Request $request) {
        $year = $request['year'];
        $month = $request['month'];
        $monthPurposeManage = MonthPurposeManage::where('user_id',$request->loginId)->where('year',$year)->where('month',$month)->first();
        $monthAchiveManage = MonthAchiveManage::where('user_id',$request->loginId)->where('year',$year)->where('month',$month)->first();
        $monthGoalImageManage = MonthGoalImageManage::where('user_id',$request->loginId)->where('year',$year)->where('month',$month)->first();
        $monthPassionManage = MonthPassionManage::where('user_id',$request->loginId)->where('year',$year)->where('month',$month)->first();

        $response = [
            $monthPurposeManage, $monthAchiveManage, $monthGoalImageManage, $monthPassionManage
        ];
        return response()->json($response);
    }

    // 目標管理モーダルから「目的」テキストを編集する
    public function purposeEdit(Request $request) {
        $purposeText = $request['purposeText'];
        $year = $request['year'];
        $month = $request['month'];
        $loginId = $request['loginId'];
        if (MonthPurposeManage::where('user_id',$loginId)->where('year',$year)->where('month',$month)->exists()) {
            MonthPurposeManage::where('user_id',$loginId)->where('year',$year)->where('month',$month)->delete();
        }
        $monthPurposeManage = new MonthPurposeManage();
        $monthPurposeManage->user_id = $loginId;
        $monthPurposeManage->year = $year;
        $monthPurposeManage->month = $month;
        $monthPurposeManage->purpose = $purposeText;
        $monthPurposeManage->save();
    }

    // 目標管理モーダルから「目標」テキストを編集する
    public function achiveEdit(Request $request) {
        $achiveText = $request['achiveText'];
        $year = $request['year'];
        $month = $request['month'];
        $loginId = $request['loginId'];
        if (MonthAchiveManage::where('user_id',$loginId)->where('year',$year)->where('month',$month)->exists()) {
            MonthAchiveManage::where('user_id',$loginId)->where('year',$year)->where('month',$month)->delete();
        }
        $monthAchiveManage = new MonthAchiveManage();
        $monthAchiveManage->user_id = $loginId;
        $monthAchiveManage->year = $year;
        $monthAchiveManage->month = $month;
        $monthAchiveManage->achive = $achiveText;
        $monthAchiveManage->save();
    }

    // 目標管理モーダルから「ゴールイメージ」テキストを編集する
    public function goalImageEdit(Request $request) {
        $goalImageText = $request['goalImageText'];
        $year = $request['year'];
        $month = $request['month'];
        $loginId = $request['loginId'];
        if (MonthGoalImageManage::where('user_id',$loginId)->where('year',$year)->where('month',$month)->exists()) {
            MonthGoalImageManage::where('user_id',$loginId)->where('year',$year)->where('month',$month)->delete();
        }
        $monthGoalImageManage = new MonthGoalImageManage();
        $monthGoalImageManage->user_id = $loginId;
        $monthGoalImageManage->year = $year;
        $monthGoalImageManage->month = $month;
        $monthGoalImageManage->goal_image = $goalImageText;
        $monthGoalImageManage->save();
    }

    // 目標管理モーダルから「意気込み」テキストを編集する
    public function passionEdit(Request $request) {
        $passionText = $request['passionText'];
        $year = $request['year'];
        $month = $request['month'];
        $loginId = $request['loginId'];
        if (MonthPassionManage::where('user_id',$loginId)->where('year',$year)->where('month',$month)->exists()) {
            MonthPassionManage::where('user_id',$loginId)->where('year',$year)->where('month',$month)->delete();
        }
        $monthPassionManage = new MonthPassionManage();
        $monthPassionManage->user_id = $loginId;
        $monthPassionManage->year = $year;
        $monthPassionManage->month = $month;
        $monthPassionManage->passion = $passionText;
        $monthPassionManage->save();
    }
}
