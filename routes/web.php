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



$router->model('category_url', 'App\CategoryTranslation');

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')
    ->name('home');

Route::get('/gnome/{gnome}', 'HomeController@view')
    ->where('id', '^([0-9]+)')
    ->name('gnome_view');

Route::post('/gnome/{gnome}', 'HomeController@edit')
    ->where('id', '^([0-9]+)')
    ->name('gnome_edit');

Route::get('/gnome/delete/{gnome}', 'HomeController@delete')
    ->where('id', '^([0-9]+)')
    ->name('gnome_delete');
