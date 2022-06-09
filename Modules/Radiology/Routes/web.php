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

Route::get('getradiogroup', array(
    'as' => 'getradiogroup',
    'uses' => 'RadiologyController@index'
));

Route::group([
    'middleware' => ['web', 'auth-checker'],
    'prefix' => 'radiology',
    'as' => 'radiology.'
], function () {
    Route::group([
        'prefix' => 'template',
        'as' => 'template.',
    ], function() {
        Route::get('', 'RadiologyTemplateController@index')->name('index');
        Route::post('saveUpdate', 'RadiologyTemplateController@saveUpdate')->name('saveUpdate');
        Route::delete('delete/{id}', 'RadiologyTemplateController@delete')->name('delete');
    });
    Route::match(['get', 'post'], '', 'RadiologyTestController@index')->name('index');
    Route::get('getTests', 'RadiologyTestController@getTests')->name('getTests');
    Route::get('getPatRadioTest', 'RadiologyTestController@getPatRadioTest')->name('getPatRadioTest');
    Route::post('addPatRadioTest', 'RadiologyTestController@addPatRadioTest')->name('addPatRadioTest');
    Route::post('updateRadioTest', 'RadiologyTestController@updateRadioTest')->name('updateRadioTest');

    Route::get('getPatRadioTestPdf', 'RadiologyTestController@getPatRadioTestPdf')->name('getPatRadioTestPdf');

    Route::get('getModalContent', 'RadiologyTestController@getModalContent')->name('getModalContent');
    Route::post('updateRadioObservation', 'RadiologyTestController@updateRadioObservation')->name('updateRadioObservation');

    Route::post('addComment', 'RadiologyTestController@addComment');
    Route::post('addCondition', 'RadiologyTestController@addCondition');

    Route::get('getPacUrl', 'RadiologyTestController@getPacUrl');
    Route::get('radioHistory', 'RadiologyTestController@radioHistory')->name('radioHistory');

    // Route::post('updateTest', 'RadiologyTestController@updateTest')->name('updateTest');

    Route::match(['get', 'post'], 'printing', 'RadiologySettingController@index')->name('setting');
    // Route::match(['get', 'post'], 'verify', 'RadiologySettingController@index')->name('verify');
    Route::post('verifyReport', 'RadiologySettingController@verifyReport')->name('verifyReport');
    Route::get('printReport', 'RadiologySettingController@printReport')->name('printReport');
    Route::post('saveReport', 'RadiologySettingController@saveReport')->name('saveReport');

    Route::group([
        'prefix' => 'verify'
    ], function() {
        Route::match(['get', 'post'], '', 'RadioVerifyController@index')->name('verify');
        Route::get('getPatientTest', 'RadioVerifyController@getPatientTest');
        Route::get('getModalContent', 'RadioVerifyController@getModalContent');
        Route::post('changeStatus', 'RadioVerifyController@changeStatus');
    });

    Route::group([
        'prefix' => 'appointment'
    ], function() {
        Route::match(['get', 'post'], '', 'RadioAppointmentController@index')->name('appointment');
        Route::get('getPatientTest', 'RadioAppointmentController@getPatientTest');
        Route::post('schedule', 'RadioAppointmentController@schedule');
        Route::post('inside', 'RadioAppointmentController@inside');
    });

    Route::get('samplingPatientReport', 'RadiologyTestController@samplingPatientReport')->name('samplingPatientReport');

    Route::match(['get', 'post'], 'reporting', 'RadiologyTestController@index')->name('reporting');
});

Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'radiology', 'as' => 'radiology.'], function () {

//    Route::group(['prefix' => 'diagnostic', 'as' => 'diagnostic.'], function() {
//        Route::get('/', 'RadioDiagnosticController@index')->name('index');
//    });

    Route::group(['prefix' => 'grouping', 'as' => 'grouping.'], function () {
        Route::get('/', 'GroupingController@displayGrouping')->name('display.grouping');
        Route::post('/selectservicecost', 'GroupingController@selectServiceCostFromGroup')->name('selectservicecost');
        Route::post('/examidselect', 'GroupingController@selectExamidFromDatatype')->name('examidselect');
        Route::post('/testmethod', 'GroupingController@testMethodFromExamId')->name('testmethod');
        Route::post('/addradiogroup', 'GroupingController@addRadioGroup')->name('addradiogroup');
        Route::delete('/deleteradiogroup/{fldid}', 'GroupingController@deleteRadioGroup')->name('deleteradiogroup');
        Route::post('/loadtestongroupchange', 'GroupingController@loadTestsOnGroupChange')->name('loadtestongroupchange');
        Route::get('/exportradiogrouptopdf', 'GroupingController@exportRadioGroups')->name('exportradiogrouptopdf');
    });

});
