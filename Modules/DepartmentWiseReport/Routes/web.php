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

Route::prefix('department-wise-report')->group(function() {
    Route::get('/', array(
        'as' => 'departmentwise.report',
        'uses' => 'DepartmentWiseReportController@index'
    ));

    Route::get('/excel', array(
        'as' => 'departmentwise.report.excel',
        'uses' => 'DepartmentWiseReportController@excel'
    ));

    Route::get('/pdf', array(
        'as' => 'departmentwise.report.pdf',
        'uses' => 'DepartmentWiseReportController@pdf'
    ));
});