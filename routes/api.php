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

Route::middleware(['auth.basic'])->group(function () {
    Route::get('/user', 'ApiController@user')
        ->name('api_user_data');

    Route::get('/gnomes', 'ApiController@listGnomes')
        ->name('api_gnome_list');

    Route::get('/gnomes/{id}', 'ApiController@getGnome')
        ->where('id', '^([0-9]+)')
        ->name('api_gnome_get');

    Route::put('/gnomes', 'ApiController@createGnome')
        ->name('api_gnome_create');

    Route::patch('/gnomes/{id}', 'ApiController@editGnome')
        ->where('id', '^([0-9]+)')
        ->name('api_gnome_edit');

    Route::delete('/gnomes/{id}', 'ApiController@deleteGnome')
        ->where('id', '^([0-9]+)')
        ->name('api_gnome_delete');
});
