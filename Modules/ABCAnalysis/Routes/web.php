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

Route::prefix('abcanalysis')->group(function() {
    Route::get('/setups', 'ABCAnalysisController@setups')->name('abcanalysis.setups');
    Route::post('/saveItemClass', 'ABCAnalysisController@saveItemClass')->name('abcanalysis.saveItemClass');
    Route::post('/saveMovingType', 'ABCAnalysisController@saveMovingType')->name('abcanalysis.saveMovingType');
    Route::get('/itemClassReport', 'ABCAnalysisController@itemClassReport')->name('abcanalysis.item-class-report');
    Route::get('/movingTypeReport', 'ABCAnalysisController@movingTypeReport')->name('abcanalysis.moving-type-report');
    Route::any('/getItemClassReport', 'ABCAnalysisController@getItemClassReport')->name('abcanalysis.getItemClassReport');
    Route::any('/getMovingTypeReport', 'ABCAnalysisController@getMovingTypeReport')->name('abcanalysis.getMovingTypeReport');
    Route::any('/exportMovingTypeReportCsv', 'ABCAnalysisController@exportMovingTypeReportCsv')->name('abcanalysis.exportMovingTypeReportCsv');
    Route::any('/exportItemClassReportCsv', 'ABCAnalysisController@exportItemClassReportCsv')->name('abcanalysis.exportItemClassReportCsv');
});
