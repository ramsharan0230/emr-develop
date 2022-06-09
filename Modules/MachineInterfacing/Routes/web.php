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

Route::group([
    'middleware' => ['web', 'auth-checker'],
    'prefix' => 'admin/machine-interfacing',
    'as' => 'machine.'
], function () {

    Route::get('/list', 'MapMachineController@listMap')->name('interfacing.list');
    Route::get('/add', 'MapMachineController@add')->name('interfacing.add');
    Route::get('/edit/{id}', 'MapMachineController@edit')->name('interfacing.edit');
    Route::post('/update', 'MapMachineController@update')->name('interfacing.update');
    Route::post('/create', 'MapMachineController@create')->name('interfacing.create');
    Route::get('/delete/{id}', 'MapMachineController@delete')->name('interfacing.delete');
    Route::post('/get-sub-test', 'MapMachineController@getSubTest')->name('interfacing.get.sub.test');
});
