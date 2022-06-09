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
	'prefix' => 'ent',
], function() {
    Route::match(['get', 'post'], '/', 'ENTController@index')->name('ent');
    Route::get('/reset-encounter', 'ENTController@resetEncounter')->name('ent.reset.encounter');
    Route::post('/store', 'ENTController@store')->name('ent.store');

    Route::post('/examgeneral', 'ENTController@examgeneral')->name('ent.examgeneral');

    // OPD SHEET PDF
    Route::get('/opdsheet-pdf-report/{encounterId?}', 'ENTPdfController@opdSheetPdf')->name('ent.opd.sheet.pdf');

    Route::get('/all_history/{patientId}', 'HistoryPdfController@historypdf')->name('ent.histry.pdf');

    Route::post('/audiogram/save', 'ENTController@saveAudiogram')->name('ent.audiogram.save');
    Route::post('/audiogram/request', 'ENTController@requestAudiogram')->name('ent.audiogram.request');
    Route::post('/audiogram/perform', 'ENTController@performAudiogram')->name('ent.audiogram.perform');
    Route::post('/save-comment', 'ENTController@saveComment')->name('ent.comment.save');
    Route::get('/audiogram/report/{audiogramRequestId}', 'ENTController@audiogramreport')->name('ent.audiogram.report');
});
