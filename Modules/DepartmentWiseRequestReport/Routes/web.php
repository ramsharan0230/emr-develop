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

Route::prefix('department-wise-request-report')->group(function() {
    Route::get('/', array(
        'as' => 'departmentwise.request.report',
        'uses' => 'DepartmentWiseRequestReportController@index'
    ));

    Route::get('/', array(
        'as' => 'departmentwise.request.report',
        'uses' => 'DepartmentWiseRequestReportController@index'
    ));

    Route::get('/get-department-lists', array(
        'as' => 'department-list.request',
        'uses' => 'DepartmentWiseRequestReportController@getDepartmentLists'
    ));

    Route::any('/reports-export', array(
        'as'   => 'report.dept-wise-request.export',
        'uses' => 'DepartmentWiseRequestReportController@exportDeptReport'
    ));
});
