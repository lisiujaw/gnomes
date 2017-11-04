<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')
    ->name('home');

Route::get('/gnome/{gnome}', 'HomeController@view')
    ->where('gnome', '^([0-9]+)')
    ->name('gnome_view');

Route::post('/gnome/{gnome}', 'HomeController@edit')
    ->where('gnome', '^([0-9]+)')
    ->name('gnome_edit');

Route::get('/gnome/create', function () {
    return view('create');
})->name('gnome_create');

Route::post('/gnome/create', 'HomeController@create')
    ->name('gnome_create_post');

Route::get('/gnome/delete/{gnome}', 'HomeController@delete')
    ->where('gnome', '^([0-9]+)')
    ->name('gnome_delete');
