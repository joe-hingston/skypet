<?php


use App\AbstractFetcher;
use App\Output;

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
Route::get('journal/health/{id}', 'JournalController@health');
Route::get('journal/destroy/{id}', 'JournalController@destroy');
Route::get('output/create/{doi}', 'OutputController@create');

Route::get('/journal/test', 'JournalController@test');

Route::get('/output/all', 'OutputController@all');
Route::get('/abstract/test', function(){

    Output::where('abstract', null)->chunk(500, function($results) {
        foreach($results as $result) {
            \App\Jobs\ProcessAbstract::dispatch($result);
        }


    });

});

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
