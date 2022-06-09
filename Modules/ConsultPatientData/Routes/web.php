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

Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'consultation'], function () {

    /*
     * View Report
     */
    Route::group(['prefix' => 'visit-report'], function () {
        Route::get('/', array(
            'as'   => 'display.consultation.view.report',
            'uses' => 'VisitReportController@displayReport'
        ));
        Route::any('/search', array(
            'as'   => 'display.consultation.view.report.search',
            'uses' => 'VisitReportController@searchData'
        ));

        Route::get('/search-name/form', array(
            'as'   => 'display.consultation.view.report.search.form',
            'uses' => 'VisitReportController@displaySearchNameForm'
        ));
        Route::post('/search-name/result', array(
            'as'   => 'display.consultation.view.report.search.name',
            'uses' => 'VisitReportController@searchDataName'
        ));

        Route::get('consultation-gender-surname-district-report-visit-pdf', array(
            'as'   => 'consultation.gender.surname.district.report.visit.pdf',
            'uses' => 'VisitReportController@generatePdf'
        ));

        Route::any('/excel', array(
            'as'   => 'display.consultation.view.report.excel',
            'uses' => 'VisitReportController@excel'
        ));
    });



    /*
     * IP Events
     */
    Route::group(['prefix' => 'ip-events'], function () {
        Route::get('/', array(
            'as'   => 'display.consultation.ip.events',
            'uses' => 'IpEventsController@displayReport'
        ));

        Route::any('/search-list', array(
            'as'   => 'display.consultation.ip.events.search.list',
            'uses' => 'IpEventsController@searchList'
        ));

        Route::get('/search-name/form', array(
            'as'   => 'display.consultation.ip.events.search.form',
            'uses' => 'IpEventsController@displaySearchNameForm'
        ));
        Route::post('/search-name/result', array(
            'as'   => 'display.consultation.ip.events.search.name',
            'uses' => 'IpEventsController@searchDataName'
        ));

        Route::any('consultation-ip-events-visit-pdf', array(
            'as'   => 'consultation.ip.events.report.visit.pdf',
            'uses' => 'IpEventsController@generatePdf'
        ));
    });

    Route::get('/month-wise-admission-discharge-report', array(
        'as'   => 'month.wise.adminssion.discharge.report',
        'uses' => 'IpEventsController@monthWiseAdmissionDischarge'
    ));

    Route::get('/month-wise-admission-discharge-pdf-report', array(
        'as'   => 'month.wise.adminssion.discharge.pdf.report',
        'uses' => 'IpEventsController@monthWiseAdmissionDischargePdf'
    ));

    /*
     * Transition
     */
    Route::group(['prefix' => 'transition'], function () {
        Route::get('/', array(
            'as'   => 'display.consultation.transition',
            'uses' => 'TransitionController@displayReport'
        ));

        /*Route::get('/chart', array(
            'as'   => 'display.consultation.transition.chart',
            'uses' => 'TransitionController@displayChart'
        ));*/

        Route::any('/search-list', array(
            'as'   => 'display.consultation.transition.search.list',
            'uses' => 'TransitionController@searchList'
        ));

        Route::get('/search-name/form', array(
            'as'   => 'display.consultation.transition.search.form',
            'uses' => 'TransitionController@displaySearchNameForm'
        ));
        Route::post('/search-name/result', array(
            'as'   => 'display.consultation.transition.search.name',
            'uses' => 'TransitionController@searchDataName'
        ));
    });
});
