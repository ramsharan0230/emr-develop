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

Route::prefix('dynamicreports')->group(function() {
    Route::get('/', 'DynamicreportsController@index')->name('dynamic.report.index');
    Route::get('/edit/{id}', 'DynamicreportsController@edit')->name('dynamic.report.edit');
    Route::get('/{reportname}', 'DynamicreportsController@dynamicReport')->name('dynamic.report');
    Route::any('/report/filter', 'DynamicreportsController@filterReport')->name('dynamic.report.filter');
    Route::any('/report/excel', 'DynamicreportsController@excelReport')->name('dynamic.report.excel');
    Route::post('/store', 'DynamicreportsController@store')->name('store.dynamic.report');
    Route::post('/update', 'DynamicreportsController@update')->name('update.dynamic.report');
    Route::get('/lists/generate', 'DynamicreportsController@lists')->name('dynamic.report.lists');
    Route::get('/delete/{id}', 'DynamicreportsController@delete')->name('dynamic.report.delete');
});
