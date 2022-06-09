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

Route::prefix('itemmaster')->group(function() {

    Route::get('/', 'ItemMasterController@index')->name('itemmaster.index');

    Route::get('/create', 'ItemMasterController@create')->name('itemmaster.create');

    Route::post('/store', 'ItemMasterController@store')->name('itemmaster.store');

    Route::post('/delete', 'ItemMasterController@destroy')->name('itemmaster.delete');

    Route::any('/change-status', 'ItemMasterController@changeStatus')->name('itemmaster.status');

    Route::post('/check', 'ItemMasterController@check')->name('itemmaster.check');

    Route::post('/create-item', 'ItemMasterController@createItem')->name('itemmaster.create.item');

    Route::post('/create-department', 'ItemMasterController@createDepartment')->name('itemmaster.create.department');

    Route::get('{fldbillitem}', 'ItemMasterController@show')->name('itemmaster.show');

    Route::get('{fldbillitem}/edit', 'ItemMasterController@edit')->name('itemmaster.edit');

    Route::post('{fldbillitem}/update', 'ItemMasterController@update')->name('itemmaster.update');


});