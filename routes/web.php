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
    return view('top');
});
Route::post('/entry','EntryController@setentry');
Route::get('/entry','EntryController@getentry');
Route::post('/status','EntryController@upstatus');
Route::get('/status', 'EntryController@getstatus');
Route::post('/list', 'EntryController@liststatus');
Route::get('/list', 'EntryController@list');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
