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

use App\Output;
use GuzzleHttp\Client;

Route::get('test', function(){

    $journals = \App\Journal::all();

    foreach ($journals as $journal) {
        $fetcher = new \App\OutputFetcher(new Client(), $journal);

        $fetcher->fetch();
    }

});

Route::get('abstract', function(){
  dd(Output::whereNotNull('abstract')->get());



});

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
