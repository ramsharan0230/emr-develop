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
    Route::any('/consultation', 'ConsultationController@displayConsultation')->name('consultation');
    Route::any('/listconsultation', 'ConsultationController@listConsultation')->name('listconsultation');
    Route::any('/consultation/generatepdf', 'ConsultationController@generatepdf')->name('consultation.generatepdf');
    Route::get('/consultation/getDeptWiseConsultant', 'ConsultationController@getDeptWiseConsultant')->name('consultation.getDeptWiseConsultant');

    Route::post('display-searchenc-form-servicedata-consultant', array(
        'as'   => 'display.searchenc.form.servicedata.consultant',
        'uses' => 'ConsultationController@displaySeachEncForm'
    ));

    Route::post('display-searchname-form-servicedata-consultant', array(
        'as'   => 'display.searchname.form.servicedata.consultant',
        'uses' => 'ConsultationController@displaySeachNameForm'
    ));

    Route::post('search-patient-list-consultant', array(
        'as'   => 'search.patient.list.consultant',
        'uses' => 'ConsultationController@searchPatient'
    ));
    Route::any('consultation-summarize-report-consultant', array(
        'as' => 'consultation.summarize.report.consultant',
        'uses' => 'ReportController@summarizeReport'
    ));
    Route::any('consultation-datewise-report-consultant', array(
        'as' => 'consultation.datewise.report.consultant',
        'uses' => 'ReportController@datewiseReport'
    ));
    // search.encounter.detail.consultant
    Route::post('search-encounter-detail-consultant', array(
        'as'   => 'search.encounter.detail.consultant',
        'uses' => 'ConsultationController@encounterDetail'
    ));

    Route::post('display-patient-image', array(
        'as'   => 'display.patient.image',
        'uses' => 'ConsultationController@displayPatientImage'
    ));
    Route::post('display-last-encounter', array(
        'as'   => 'display.last.encounter',
        'uses' => 'ConsultationController@displayLastEncounter'
    ));
    Route::post('display-all-encounter', array(
        'as'   => 'display.all.encounter',
        'uses' => 'ConsultationController@displayAllEncounter'
    ));

    /*
     * Clinical Access
     */
    Route::group(['prefix' => 'consultation/clinical-access'], function () {
        Route::get('/', array(
            'as'   => 'consultant.clinical.access',
            'uses' => 'ClinicalAccessController@index'
        ));

        Route::post('/user-disabled-components', array(
            'as'   => 'consultant.clinical.access.user.disabled.components',
            'uses' => 'ClinicalAccessController@userDisabledComponents'
        ));

        Route::post('/user-delete-components', array(
            'as'   => 'consultant.clinical.access.user.delete.components',
            'uses' => 'ClinicalAccessController@userDeleteComponents'
        ));

        Route::post('/user-add-components', array(
            'as'   => 'consultant.clinical.access.user.add.components',
            'uses' => 'ClinicalAccessController@userAddComponents'
        ));
    });

    Route::post('search-patient-name-consultant', array(
        'as'   => 'search.patient.name.consultant',
        'uses' => 'ConsultationController@searchPatientByName'
    ));

    /*
     * Procedure
     */
    Route::group(['prefix' => 'consultation/procedure-report'], function () {
        Route::get('/', array(
            'as'   => 'display.consultation.procedure.report',
            'uses' => 'ProcedureReportController@displayReport'
        ));

        Route::post('/search-list', array(
            'as'   => 'display.consultation.procedure.report.search.list',
            'uses' => 'ProcedureReportController@searchList'
        ));

        Route::get('/search-name/form', array(
            'as'   => 'display.consultation.procedure.search.form',
            'uses' => 'ProcedureReportController@displaySearchNameForm'
        ));
        Route::post('/search-name/result', array(
            'as'   => 'display.consultation.procedure.search.name',
            'uses' => 'ProcedureReportController@searchDataName'
        ));
        Route::get('/generate-print', array(
            'as'   => 'display.consultation.procedure.search.generate.print',
            'uses' => 'ProcedureReportController@generatePdf'
        ));

    });

    /*
     * Equipment
     */
    Route::group(['prefix' => 'consultation/equipment'], function () {
        Route::get('/', array(
            'as'   => 'display.consultation.equipment.report',
            'uses' => 'EquipmentController@displayReport'
        ));

        Route::post('/search-list', array(
            'as'   => 'display.consultation.equipment.report.search.list',
            'uses' => 'EquipmentController@searchList'
        ));

        Route::get('/search-name/form', array(
            'as'   => 'display.consultation.equipment.search.form',
            'uses' => 'EquipmentController@displaySearchNameForm'
        ));
        Route::post('/search-name/result', array(
            'as'   => 'display.consultation.equipment.search.name',
            'uses' => 'EquipmentController@searchDataName'
        ));

        Route::any('/equipment/generate/pdf', 'EquipmentController@generatePdf')->name('equipment.generatepdf');

    });

    /*
     * confinement
     */
    Route::group(['prefix' => 'consultation/confinement'], function () {
        Route::get('/', array(
            'as'   => 'display.consultation.confinement.report',
            'uses' => 'ConfinementReportController@displayReport'
        ));

        Route::any('/search-list', array(
            'as'   => 'display.consultation.confinement.report.search.list',
            'uses' => 'ConfinementReportController@searchData'
        ));

        Route::get('/search-name/form', array(
            'as'   => 'display.consultation.confinement.search.form',
            'uses' => 'ConfinementReportController@displaySearchNameForm'
        ));
        Route::post('/search-name/result', array(
            'as'   => 'display.consultation.confinement.search.name',
            'uses' => 'ConfinementReportController@searchDataName'
        ));
        Route::post('/export', array(
            'as'   => 'display.consultation.confinement.export',
            'uses' => 'ConfinementReportController@export'
        ));
    });

});
