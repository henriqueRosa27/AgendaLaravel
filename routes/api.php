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

Route::namespace('API')->name('api.')->group(function(){
    Route::prefix('/agenda')->group(function(){
        Route::get('/', 'AgendaController@index')->name('index_agenda');
        Route::get('/{id}', 'AgendaController@show')->name('detalhe_agenda');
        Route::post('/', 'AgendaController@store')->name('store_agenda');
        Route::put('/{id}', 'AgendaController@update')->name('update_agenda');
        Route::delete('/{id}', 'AgendaController@delete')->name('delete_agenda');
    });
});
