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

Route::get('/','MainController@index');

Route::get('habitPost','HabitController@habitPost')->name('habitPost');
Route::get('habitGet','HabitController@habitGet')->name('habitGet');
Route::get('habitDelete','HabitController@habitDelete')->name('habitDelete');
Route::get('habitResult','HabitController@habitResult')->name('habitResult');
Route::get('habitResultGet','HabitController@habitResultGet')->name('habitResultGet');
Route::get('habitGetDateResult','HabitController@habitGetDateResult')->name('habitGetDateResult');
Route::get('habitGetMonthAchiveRate', 'HabitController@habitGetMonthAchiveRate')->name('habitGetMonthAchiveRate');
Route::get('getHabitAchiveRate', 'HabitController@getHabitAchiveRate')->name('getHabitAchiveRate');
Route::get('registerAchiveRate', 'HabitController@registerAchiveRate')->name('registerAchiveRate');
Route::get('habitGetItemAndAchiveRate', 'HabitController@habitGetItemAndAchiveRate')->name('habitGetItemAndAchiveRate');
Route::get('purposeRegister', 'HabitController@purposeRegister')->name('purposeRegister');
Route::get('achiveRegister', 'HabitController@achiveRegister')->name('achiveRegister');
Route::get('goalImageRegister', 'HabitController@goalImageRegister')->name('goalImageRegister');
Route::get('passionRegister', 'HabitController@passionRegister')->name('passionRegister');
Route::get('confirmExistsAchiveData', 'HabitController@confirmExistsAchiveData')->name('confirmExistsAchiveData');
Route::get('purposeEdit', 'HabitController@purposeEdit')->name('purposeEdit');
Route::get('achiveEdit', 'HabitController@achiveEdit')->name('achiveEdit');
Route::get('goalImageEdit', 'HabitController@goalImageEdit')->name('goalImageEdit');
Route::get('passionEdit', 'HabitController@passionEdit')->name('passionEdit');