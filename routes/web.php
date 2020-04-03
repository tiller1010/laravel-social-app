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

// Route::resource('/messages', 'MessagesController');

Route::get('/messages', 'MessagesController@index');
Route::get('/messages/create', 'MessagesController@create');
// Route::get('messages/{project}', 'MessagesController@show');
Route::post('/messages', 'MessagesController@store');
Route::get('/messages/{project}/edit', 'MessagesController@edit');
Route::patch('messages/{project}', 'MessagesController@update');
Route::delete('messages/{project}', 'MessagesController@destroy');


Route::get('/messages/{connectedUser}', 'MessagesController@show');

Auth::routes();

Route::get('/', 'HomeController@index')->name('home')->middleware('auth');
Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');
