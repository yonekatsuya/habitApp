<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// トップページへのアクセス
Route::get('/','MainController@index');

// 習慣項目登録処理
Route::get('habitPost','HabitController@habitPost')->name('habitPost');
// 習慣項目取得処理
Route::get('habitGet','HabitController@habitGet')->name('habitGet');
// 習慣項目削除処理
Route::get('habitDelete','HabitController@habitDelete')->name('habitDelete');


Route::get('habitResult','HabitController@habitResult')->name('habitResult');
Route::get('habitResultGet','HabitController@habitResultGet')->name('habitResultGet');
Route::get('habitGetDateResult','HabitController@habitGetDateResult')->name('habitGetDateResult');
Route::get('habitGetMonthAchiveRate', 'HabitController@habitGetMonthAchiveRate')->name('habitGetMonthAchiveRate');
Route::get('habitGetItemAndAchiveRate', 'HabitController@habitGetItemAndAchiveRate')->name('habitGetItemAndAchiveRate');

// 目標管理モーダルからのリクエスト
Route::get('getHabitAchiveRate', 'AchiveManageModalController@getHabitAchiveRate')->name('getHabitAchiveRate');

Route::get('registerAchiveRate', 'AchiveManageModalController@registerAchiveRate')->name('registerAchiveRate');

Route::get('purposeRegister', 'AchiveManageModalController@purposeRegister')->name('purposeRegister');

Route::get('achiveRegister', 'AchiveManageModalController@achiveRegister')->name('achiveRegister');

Route::get('goalImageRegister', 'AchiveManageModalController@goalImageRegister')->name('goalImageRegister');

Route::get('passionRegister', 'AchiveManageModalController@passionRegister')->name('passionRegister');

Route::get('confirmExistsAchiveData', 'AchiveManageModalController@confirmExistsAchiveData')->name('confirmExistsAchiveData');

Route::get('purposeEdit', 'AchiveManageModalController@purposeEdit')->name('purposeEdit');

Route::get('achiveEdit', 'AchiveManageModalController@achiveEdit')->name('achiveEdit');

Route::get('goalImageEdit', 'AchiveManageModalController@goalImageEdit')->name('goalImageEdit');

Route::get('passionEdit', 'AchiveManageModalController@passionEdit')->name('passionEdit');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('login/google', 'Auth\LoginController@redirectGoogle');
Route::get('auth/login/facebook', 'Auth\LoginController@redirectFacebook');
Route::get('auth/login/twitter', 'Auth\LoginController@redirectTwitter');
Route::get('login/google/callback', 'Auth\LoginController@handleGoogleCallback');
Route::get('auth/facebook/callback', 'Auth\LoginController@handleFacebookProviderCallback');
Route::get('auth/twitter/callback', 'Auth\LoginController@handleTwitterCallback');