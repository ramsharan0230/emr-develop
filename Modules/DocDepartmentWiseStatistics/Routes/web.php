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

Route::prefix('departmentwisestatistics')->group(function() {
    Route::get('/', 'DocDepartmentWiseStatisticsController@dynamicReport')->name('dynamic.statistics.index');
    Route::any('/statistics/filter', 'DocDepartmentWiseStatisticsController@filterReport')->name('dynamic.statistics.filter');
    Route::get('/report/pdf', 'DocDepartmentWiseStatisticsController@dynamicReportPdf')->name('dynamic.statistics.report');
    Route::any('/report/excel', 'DocDepartmentWiseStatisticsController@excelReport')->name('dynamic.statistics.excel');
    // Route::post('/store', 'DynamicreportsController@store')->name('store.dynamic.report');
    // Route::post('/update', 'DynamicreportsController@update')->name('update.dynamic.report');
    // Route::get('/lists/generate', 'DynamicreportsController@lists')->name('dynamic.report.lists');
    // Route::get('/delete/{id}', 'DynamicreportsController@delete')->name('dynamic.report.delete');
});