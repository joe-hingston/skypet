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

use App\Jobs\ProcessDois;
use App\Jobs\ProcessJournal;
use App\Journal;

Route::get('doi', function () {
    $journal = Journal::firstorNew(['issn' => '0891-6640']);
    $journal->save();



    $journal = Journal::find(1);
    //Process specific DOIs
    ProcessDois::dispatch('10.1111/j.1939-1676.2009.0352.x', $journal)->onConnection('redis')->onQueue('journals');
});

Route::get('journalseed', function () {

    //Add new Journal to the que
    ProcessJournal::dispatch('0891-6640')->onConnection('redis')->onQueue('journals');



});

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
