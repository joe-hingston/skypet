<?php


use App\AbstractFetcher;
use App\Journal;
use App\Output;
use App\User;

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


Route::get('/test',function(){
    $journal = \App\Journal::first();
    $journal->notify(new \App\Notifications\JournalAdded($journal));
});
Route::get('/testget',function(){
    $user = \App\User::find(1);
    foreach ($user->notifications as $notification) {
        echo $notification->type;
    }
});




Route::group([ 'middleware' => ['role:Admin']], function() {
    Route::post('journal/create/{issn}', 'JournalController@create');
    Route::get('journal/destroy/{id}', 'JournalController@destroy');
    Route::post('journals/store/{id}', 'JournalController@store');
    Route::get('journal/health/{id}', 'JournalController@health');
    Route::get('/abstract/all', 'OutputController@abstractsall');
    Route::get('output/create/{doi}', 'OutputController@create');
    Route::resource('users', 'UserController');
    Route::resource('roles', 'RoleController');
    Route::resource('posts', 'PostController');
    Route::resource('healths', 'HealthController');
});

Route::group([ 'middleware' => 'auth' ], function () {
    // ...
    Route::get('/notifications', 'UserController@notifications');

    Route::get('/home', 'HomeController@index');

});

Route::resource('outputs', 'OutputController');
Route::resource('journals', 'JournalController');
Route::get('journals/{id}', 'JournalController@show');
Route::get('/', 'HomeController@index');


Auth::routes();

Route::get('/output/search', function (\App\Outputs\OutputsRepository $repository) {

    if (request('q')) {

        //TODO SET LIMIT FOR SEARCHING
        $limit = 200;
        $articles = $repository->search(request('q'), $limit);
    }

//    if (request('w')) {
//
//        //TODO SET LIMIT FOR SEARCHING
//        $limit = 1;
//        $articles = $repository->searchDOI(request('w'), $limit);
//    }

    else {
        $articles = \App\Output::all();
    }

    return view('layouts.output.index', [
        'Outputs' => $articles,
    ]);
});
