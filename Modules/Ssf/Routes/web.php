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

Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'ssf'], function () {

    Route::get('/test', 'SsfController@test');
    Route::get('/patient-detail/{patientId?}', 'SsfController@getPatientDeatailById')->name('ssf.get-patient-detail');
    Route::get('/check-eligibility/{patientId}', 'SsfController@checkEligibilityByPatientId')->name('ssf.check-eligibility');
    Route::get('/claim-submission', 'SsfController@claimSubmission')->name('ssf.claim-submission');
});
