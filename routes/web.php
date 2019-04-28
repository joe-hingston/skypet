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
use App\Jobs\ProcessDois;
use App\Jobs\ProcessJournal;
use App\Output;
use App\Providers\HelperServiceProvider;
use GuzzleHttp\Client;
use hamburgscleanest\LaravelGuzzleThrottle\Facades\LaravelGuzzleThrottle;


Route::get('journalseed', function () {

     ProcessJournal::dispatch('0891-6640');
});


Auth::routes();


