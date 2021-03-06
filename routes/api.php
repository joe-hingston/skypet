<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => \Barryvdh\Cors\HandleCors::class], function() {
    Route::resource('/output', 'API\outputs', [
        'except' => ['edit', 'show', 'store']
    ]);
});


Route::resource('outputs', 'API\Outputs');
Route::resource('journals', 'API\Journals');
Route::delete('journals', 'API\Journals@destroybyISSN');
