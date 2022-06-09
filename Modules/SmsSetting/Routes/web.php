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

Route::prefix('smssetting')->group(function() {
    Route::get('/', 'SmsSettingController@index')->name('smssetting.index');

    Route::post('/save-sms','SmsSettingController@store')->name('smssetting.savesms');

    Route::post('/search-sms','SmsSettingController@search')->name('smssetting.searchsms');

    Route::post('/search-sms-name','SmsSettingController@searchname')->name('smssetting.searchname');

    Route::get('/reset','SmsSettingController@reset')->name('smssetting.reset');

    Route::post('/update-sms','SmsSettingController@update')->name('smssetting.updatesms');

    Route::delete('delete/{id}','SmsSettingController@delete')->name('smssetting.delete');

    Route::post('/clone-sms','SmsSettingController@clone')->name('smssetting.clonesms');
});