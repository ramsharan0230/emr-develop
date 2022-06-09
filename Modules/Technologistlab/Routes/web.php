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

Route::group(['middleware' =>['web', 'auth-checker'], 'prefix' => 'technologylab', 'as' => 'technologylab.'], function() {

    Route::group(['prefix' => 'grouping', 'as' => 'grouping.'], function() {
        Route::get('/', 'LabGroupingController@displayForm')->name('display.form');
        Route::post('/selectservicecost', 'LabGroupingController@selectServiceCostFromGroup')->name('selectservicecost');
        Route::post('/examidselect', 'LabGroupingController@selectExamidFromDatatype')->name('examidselect');
        Route::post('/testmethod', 'LabGroupingController@testMethodFromExamId')->name('testmethod');
        Route::post('/addtestgroup', 'LabGroupingController@addTestGroup')->name('addtestgroup');
        Route::delete('/deletetestgroup/{fldid}', 'LabGroupingController@deleteTestGroup')->name('deletetestgroup');
        Route::post('/loadtestongroupchange', 'LabGroupingController@loadTestsOnGroupChange')->name('loadtestongroupchange');
        Route::get('/exportlabgrouptopdf', 'LabGroupingController@exportLabGroups')->name('exportlabgrouptopdf');
    });

});
