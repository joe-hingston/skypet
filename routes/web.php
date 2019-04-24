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


use App\Jobs\ProcessJournal;
use App\Output;


Route::get('journalseed', function () {
    ProcessJournal::dispatch('0891-6640')->onConnection('redis')->onQueue('journals');
});



Auth::routes();


