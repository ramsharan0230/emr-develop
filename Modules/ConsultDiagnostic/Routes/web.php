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

Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => ''], function () {

	#Exmaination
    Route::get('/consultation/examination-form', array(
        'as'   => 'consultant.diagnostic.examination.form',
        'uses' => 'ExaminationReportController@displayExaminationForm'
    ));

    Route::post('/consultation/exam-list', array(
        'as'   => 'list.exams.form.diagnostic.consultant',
        'uses' => 'ExaminationReportController@listExamByCat'
    ));

    Route::post('/consultation/sub-exam-list', array(
        'as'   => 'list.subexams.form.diagnostic.consultant',
        'uses' => 'ExaminationReportController@listSubExam'
    ));

    Route::post('/consultation/exam-report', array(
        'as'   => 'search.examination.form.diagnostic.consultant',
        'uses' => 'ExaminationReportController@searchExam'
    ));


    Route::get('/consultation/export-exam-report', array(
        'as'   => 'export.examination.report.diagnostic.consultant',
        'uses' => 'ExaminationReportController@exportExamReport'
    ));


    // Route::get('/consultation/examination-report/', 'InpatientController@getInitialDiagnosisCategoryAjaxs')->name('getInitialDiagnosisCategoryAjaxs');
    // Route::get('test-pdf', array(
    //     'as' => 'view.test.pdf',
    //     'uses' => 'TestSamplingController@getTestPdf'
    // ));

    #Laboratory
    Route::get('/consultation/laboratory-form', array(
        'as'   => 'consultant.diagnostic.laboratory.form',
        'uses' => 'LaboratoryReportController@displayLaboratoryForm'
    ));

    Route::post('/consultation/lab-test-list', array(
        'as'   => 'list.test.form.diagnostic.consultant',
        'uses' => 'LaboratoryReportController@listTestByCat'
    ));
    Route::post('/consultation/lab-subtest-list', array(
        'as'   => 'list.subtests.form.diagnostic.consultant',
        'uses' => 'LaboratoryReportController@listSubTest'
    ));

    Route::post('/consultation/laboratory-report', array(
        'as'   => 'search.diagnostic.form.diagnostic.consultant',
        'uses' => 'LaboratoryReportController@searchLabTest'
    ));

    Route::get('/consultation/export-laboratory-report', array(
        'as'   => 'export.laboratory.report.diagnostic.consultant',
        'uses' => 'LaboratoryReportController@exportLabReport'
    ));
    #Sensitivity
    Route::get('/consultation/sensitivity-form', array(
        'as'   => 'consultant.diagnostic.sensitivity.form',
        'uses' => 'SensitivityReportController@displaySensitivityForm'
    ));
    Route::post('/consultation/sensitivity-report', array(
        'as'   => 'search.sensitivity.form.diagnostic.consultant',
        'uses' => 'SensitivityReportController@searchSensiTest'
    ));

    #Radiology

    Route::get('/consultation/radiology-form', array(
        'as'   => 'consultant.diagnostic.radiology.form',
        'uses' => 'RadiologyReportController@displayRadiologyForm'
    ));

    Route::post('/consultation/radiology-report', array(
        'as'   => 'search.radiology.form.diagnostic.consultant',
        'uses' => 'RadiologyReportController@searchRadiology'
    ));
    Route::get('/consultation/export-radiology-report', array(
        'as'   => 'export.radiology.report.diagnostic.consultant',
        'uses' => 'RadiologyReportController@exportRadiologyReport'
    ));

    /**
     * Lab Discharge report
     */
    Route::get('/consultation/laboratory-discharge-form', array(
        'as'   => 'consultant.diagnostic.laboratoryDischarge.form',
        'uses' => 'LaboratoryReportController@displayLabDischargeForm'
    ));
    Route::post('/consultation/search-laboratory-discharge-form', array(
        'as'   => 'consultant.diagnostic.searchLaboratoryDischarge',
        'uses' => 'LaboratoryReportController@searchLabDischargeForm'
    ));
    Route::get('/consultation/export-laboratory-discharge-report', array(
        'as'   => 'consultant.diagnostic.exportLabDischargeForm',
        'uses' => 'LaboratoryReportController@exportLabDischargeForm'
    ));

});
