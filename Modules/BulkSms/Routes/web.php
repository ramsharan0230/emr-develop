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

Route::prefix('bulksms')->group(function() {
    Route::get('/', 'BulkSmsController@index')->name('bulksms.index');

    Route::get('create',[
        'as' => 'bulksms.create',
        'uses' => 'BulkSmsController@create'
    ]);

    Route::post('store',[
        'as' => 'bulksms.store',
        'uses' => 'BulkSmsController@store'
    ]);

    Route::get('edit/{id}',[
        'as' => 'bulksms.edit',
        'uses' => 'BulkSmsController@edit'
    ]);

    Route::post('update/{id}',[
        'as' => 'bulksms.update',
        'uses' => 'BulkSmsController@update'
    ]);

    Route::delete('delete/{id}',[
        'as' => 'bulksms.delete',
        'uses' => 'BulkSmsController@delete'
    ]);

    Route::get('send/{id}',[
        'as' => 'bulksms.send',
        'uses' => 'BulkSmsController@send'
    ]);
});
