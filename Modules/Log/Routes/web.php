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

Route::prefix('log')->group(function() {
    Route::get('/', 'LogController@index')->name('logs');

    Route::get('/access', 'LogController@access')->name('logs.access');
    Route::get('/event', 'LogController@event')->name('logs.event');
    Route::get('/lab', 'LogController@labOperation')->name('logs.lab');
    Route::get('/sms', 'LogController@sms')->name('logs.sms');
    // Route::get('/pat-info', 'LogController@patInfo')->name('logs.pat');
    Route::get('/error', 'LogController@error')->name('logs.error');

    // Route::get('view', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);
});
