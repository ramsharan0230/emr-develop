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

Route::group(['middleware' =>['web', 'auth-checker'], 'prefix' => 'follow-up', 'as' => 'follow.up.'], function() {
    Route::get('/', 'FollowUpController@index')->name('index');

    Route::post('/search', 'FollowUpController@search')->name('search');
    Route::post('/search-by-encounter', 'FollowUpController@searchByEncounter')->name('search.by.encounter');
    Route::post('/update-follow-up-date-time', 'FollowUpController@updateFollowUpDate')->name('update.follow.up.date.time');
});
