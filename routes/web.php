<?php


set_time_limit(0);
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


Route::get('journal/create/{issn}', 'JournalController@create');
Route::get('output/create/{doi}', 'OutputController@create');

Route::get('/journal/test', 'JournalController@test');
Route::get('/output/test', 'OutputController@test');

Auth::routes();


