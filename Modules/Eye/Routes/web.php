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
	'prefix' => 'eye',
], function() {
    Route::match(['get', 'post'], '/', 'EyeController@index')->name('eye');
    Route::get('/reset-encounter', 'EyeController@resetEncounter')->name('eye.reset.encounter');
    Route::post('/store', 'EyeController@store')->name('eye.store');

    Route::post('/examgeneral', 'EyeController@examgeneral')->name('eye.examgeneral');

    // OPD SHEET PDF
    Route::get('/opdsheet-pdf-report/{encounterId?}', 'EyePdfController@opdSheetPdf')->name('eye.opd.sheet.pdf');

    Route::get('/all_history/{patientId}', 'HistoryPdfController@historypdf')->name('eye.histry.pdf');
});
