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
    Route::prefix('patient-list')->group(function() {
        Route::any('/', 'PatientController@list')->name('patient.list');
        Route::get('patientListCsv', 'PatientController@patientListCsv')->name('patient.patientListCsv');
	    Route::get('patientListPdf', 'PatientController@patientListPdf')->name('patient.patientListPdf');
        Route::get('patientFollowListCsv', 'PatientController@patientFollowListCsv')->name('patient.patientFollowListCsv');
	    Route::get('patientFollowListPdf', 'PatientController@patientFollowListPdf')->name('patient.patientFollowListPdf');

        Route::get('sms-email', 'PatientEmailAndSmsController@index')->name('sms.email');
        Route::post('send-email', 'PatientEmailAndSmsController@sendEmail')->name('send.email');
        Route::post('send-sms', 'PatientEmailAndSmsController@sendSms')->name('send.sms');
        Route::post('updatePatient','PatientController@updatePatient')->name('patient.update');
        Route::any('/update-consultation', 'PatientController@UpdateConsultantList')->name('update-consultant-list-followup');
        Route::get('/reset','PatientController@reset')->name('patient.reset');
    });
});