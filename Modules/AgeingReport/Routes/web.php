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

Route::prefix('ageingreport')->group(function() {
    Route::get('/', 'AgeingReportController@index')->name('ageing.report');

    Route::get('/report', 'AgeingReportController@report')->name('ageing.report.export');

    Route::get('/mapping', 'AgeingReportController@setting')->name('ageing.setting.mapping');
    Route::any('/savesetinterval', 'AgeingReportController@savesetinterval')->name('ageing.setting.interval.save');
    Route::post('/mapping/add', 'AgeingReportController@settingadd')->name('ageing.setting.map');
    Route::get('/mapping/getPageAccountLedger', 'AgeingReportController@getPageAccountLedger')->name('ageing.ledger.select');


});
