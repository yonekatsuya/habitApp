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