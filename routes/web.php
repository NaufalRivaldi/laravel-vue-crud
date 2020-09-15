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

Route::get('/', function(){
    return redirect()->route('item.index');
});

Route::get('item/get/data', 'ItemController@getData')->name('item.get.data');
Route::resource('item', 'ItemController');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
