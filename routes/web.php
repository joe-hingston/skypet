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

use App\Jobs\ProcessAbstract;
use App\Jobs\ProcessJournal;

Route::get('doiseed', function () {
    $this->doi = '10.1111/j.1939-1676.2009.0352.x';
    ProcessAbstract::dispatch($this->doi)->onConnection('redis')->onQueue('abstracts');
});

Route::get('journalseed', function () {
    $this->issn = '1939-1676';
    ProcessJournal::dispatch($this->issn)->onConnection('redis')->onQueue('journals'); //grab the abstract
});

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
