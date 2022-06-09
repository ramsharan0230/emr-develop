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

Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'bedoccupancy'], function () {
    Route::get('/', 'BedoccupancyController@index')->name('bedoccupancy');
    Route::any('/', array(
            'as' => 'submit.bed.form',
            'uses' => 'BedoccupancyController@index'
        ));

    // Route::any('/submit-bed', array(
    //     'as' => 'submit.bed.form',
    //     'uses' => 'BedoccupancyController@searchBed'
    // ));

    Route::post('/setsessionbed', array(
        'as' => 'setsessionbed',
        'uses' => 'BedoccupancyController@setsessionbed'
    ));

    Route::get('/getBedOccupacyDetails', 'BedoccupancyController@getBedOccupacyDetails')->name('bedoccupancydetails');
});
