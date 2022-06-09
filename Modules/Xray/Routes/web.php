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
	'prefix' => 'xray',
], function() {
    Route::get('/', 'XrayController@index')->name('xray');

    Route::get('getPatientTest', 'XrayController@getPatientTest');
    Route::get('changeStatus', 'XrayController@changeStatus');
    Route::post('savecomment', 'XrayController@savecomment');
    Route::post('saveAppointment', 'XrayController@saveAppointment');
});
