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

Route::get('/users',[
    'uses'=>'ChatController@getUsers',
]);
Route::get('/receiver/{receiver_id}/messages',[
    'uses'=>'ChatController@getMessages'
]);
Route::post('/messages',[
    'uses'=>'ChatController@postMessages'
]);


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
