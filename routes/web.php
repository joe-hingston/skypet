<?php


use GuzzleHttp\Client;

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

Route::get('/{any?}', function(){
    return view ('app');

})->where('any', '.*');



//
//Route::get('/testget',function(){
//    $user = \App\User::find(1);
//    foreach ($user->notifications as $notification) {
//        echo $notification->type;
//    }
//});
//
//
//
//
//Route::group([ 'middleware' => ['role:Admin']], function() {
//    Route::post('journal/create/{issn}', 'JournalController@create');
//    Route::get('journal/destroy/{id}', 'JournalController@destroy');
//    Route::post('journals/store/{id}', 'JournalController@store');
//    Route::get('journal/health/{id}', 'JournalController@health');
//    Route::get('/abstract/all', 'OutputController@abstractsall');
//    Route::get('output/create/{doi}', 'OutputController@create');
//    Route::resource('users', 'UserController');
//    Route::resource('roles', 'RoleController');
//    Route::resource('posts', 'PostController');
//    Route::resource('healths', 'HealthController');
//});
//
//Route::group([ 'middleware' => 'auth' ], function () {
//    // ...
//    Route::get('/notifications', 'UserController@notifications');
//
//    Route::get('/home', 'HomeController@index');
//
//});
//
//Route::resource('outputs', 'OutputController');
//Route::resource('journals', 'JournalController');
//Route::get('journals/{id}', 'JournalController@show');
//Route::get('/', 'HomeController@index');
//
//Route::get('/test', function(){
//    $this->issn = '1090-0233';
//    $journal = new \App\JournalFetcher($this->issn);
//    $journal->fetch();
//});
//
//
//
//Auth::routes();
//
//Route::get('/output/search', function (\App\Outputs\OutputsRepository $repository) {
//
//    if (request('q')) {
//
//        //TODO SET LIMIT FOR SEARCHING
//        $limit = 200;
//        $articles = $repository->search(request('q'), $limit);
//    }
//
////    if (request('w')) {
////
////        //TODO SET LIMIT FOR SEARCHING
////        $limit = 1;
////        $articles = $repository->searchDOI(request('w'), $limit);
////    }
//
//    else {
//        $articles = \App\Output::all();
//    }
//
//    return view('output.index', [
//        'Outputs' => $articles,
//    ]);
//});
