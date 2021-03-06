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

Route::get('/', 'StaticPagesController@home')->name('home');
Route::get('/help', 'StaticPagesController@help')->name('help');
Route::get('/about', 'StaticPagesController@about')->name('about');

Route::get('signup', 'UsersController@create')->name('signup');
Route::resource('users', 'UsersController');
/*Route::get('/users', 'UsersController@index')->name('users.index');
Route::get('/users/{user}', 'UsersController@show')->name('users.show');
Route::get('/users/create', 'UsersController@create')->name('users.create');
Route::post('/users', 'UsersController@store')->name('users.store');
Route::get('/users/{user}/edit', 'UsersController@edit')->name('users.edit');
Route::patch('/users/{user}', 'UsersController@update')->name('users.update');
Route::delete('/users/{user}', 'UsersController@destroy')->name('users.destroy');*/
Route::get('/users/{user}/pay','UsersController@payCenter')->name('users.payCenter');
Route::patch('/users/{user}/pay','UsersController@payBalance')->name('users.payBalance');
Route::get('/users/{user}/rider','UsersController@rider')->name('users.rider');
Route::patch('/users/{user}/riding','UsersController@riding')->name('users.riding');
Route::get('/users/{user}/bikes/{value}/using','UsersController@using')->name('users.using');
Route::post('users/{user}/used','UsersController@used')->name('users.used');
Route::post('users/{user}/track','UsersController@track')->name('users.track');
Route::get('users/{rider}/cloudtrack','UsersController@cloudtrack')->name('users.cloudtrack');
Route::post('users/{rider}/cloudtrack','UsersController@setcloudtrack')->name('users.setcloudtrack');
Route::get('/users/{user}/riders','UsersController@riders')->name('users.riders');
//Route::patch('users/{user}/bikes/{bike}/used','UsersController@used')->name('users.used');
//Route::get('/users/{user}/riding','BikesController@riding')->name('bikes.riding');

Route::get('login', 'SessionsController@create')->name('login');
Route::post('login', 'SessionsController@store')->name('login');
Route::delete('logout', 'SessionsController@destroy')->name('logout');
//单车
Route::resource('bikes', 'BikesController', ['only' => ['index', 'show', 'create', 'store', 'update', 'edit', 'destroy']]);


