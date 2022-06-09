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

Route::group(['middleware' =>['web', 'auth-checker'], 'prefix'=> 'hmisreport'], function() {
//    Route::get('/', 'HmisreportController@index');
    Route::get('/', 'HmisreportController@index')->name('hmisreport.index');
    Route::get('/generate', 'HmisreportController@generateReport')->name('generate.report');
    Route::get('/getLastDate', 'HmisreportController@getLastDate')->name('get.last.date');
});
