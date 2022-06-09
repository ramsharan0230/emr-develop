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

Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'discharge'], function () {
    Route::match(['get', 'post'], '/', 'DischargeController@index')->name('discharge');
    Route::match(['get', 'post'], '/get-patient', 'DischargeController@getPatientDepartmentWise')->name('patient-department-wise');
    //Diagnosis ko route
    Route::match(['get', 'post'], '/get-diagnosis', 'DischargeController@getDiagnosis')->name('populate-patient-diagnosis');
    Route::match(['get', 'post'], '/get-complaints', 'DischargeController@getComplaints')->name('populate-patient-complaints');
    Route::match(['get', 'post'], '/get-onexamination', 'DischargeController@getonExamination')->name('populate-patient-onExamination');
    Route::match(['get', 'post'], '/get-operationPerformed', 'DischargeController@operationPerformed')->name('populate-patient-operationPerformed');
    Route::match(['get', 'post'], '/get-medicine', 'DischargeController@medicine')->name('populate-patient-medicine');
    Route::match(['get', 'post'], '/get-pastHistory', 'DischargeController@pastHistory')->name('populate-patient-pastHistory');
    Route::match(['get', 'post'], '/get-patientProfile', 'DischargeController@getPatientProfile')->name('populate-patient-profile');
    Route::match(['get', 'post'], '/reset-encounter', 'DischargeController@resetEncounter')->name('discharge.reset-encounter');
    Route::post('/discharge-laboratory-list', 'DischargeController@listLabTest')->name('discharge.lab.list');
    Route::post('/discharge-lab-details', 'DischargeController@getLabDetails')->name('discharge.lab.details');
    Route::post('/discharge-radiology-list', 'DischargeController@listRadioTest')->name('discharge.radio.list');
    Route::post('/discharge-radio-details', 'DischargeController@getRadioDetails')->name('discharge.radio.details');
    Route::post('/saveDischarge', 'DischargeController@saveDischarge')->name('saveDischarge');
    Route::post('/save', 'DischargeController@save')->name('save');
    Route::get('/dischargeCertificate', 'DischargeController@dischargeCertificate')->name('dischargeCertificate');
    Route::post('/discharge-doctors-list', 'DischargeController@listDoctors')->name('discharge.doctors.list');
    Route::post('/discharge-doctors', 'DischargeController@dischargeDoctors')->name('discharge.doctors');
    Route::post('/deleteDiagnosis', 'DischargeController@deleteDiagnosis')->name('discharge.deleteDiagnosis');
    Route::post('/display-obstetric-freetext-form-final', 'DischargeController@getFinalDiagnosisfreetext')->name('discharge.diagnosis.freetext.final');
    Route::post('/display-final-obstetric-freetext-save-waiting', 'DischargeController@saveDiagnosisCustom')->name('discharge.final.diagnosis.freetext.save.waiting');
    Route::post('/display-final-obstetric-form', 'DischargeController@getFinalObstetricData')->name('discharge.diagnosis.final.obstetric');
    Route::post('/display-final-obstetric-form-save-waiting', 'DischargeController@saveFinalObstetricRequest')->name('discharge.final.obstetric.form.save.waiting');
    Route::post('/discharge-diagnosisStore', 'DischargeController@diagnosisStore')->name('discharge.diagnosisStore');
    Route::post('/medicineRequest', 'DischargeController@medicineRequest')->name('discharge.medicineRequest');
    Route::post('/medicine-saveNewOrder', 'DischargeController@saveNewOrder')->name('discharge.saveNewOrder');
    Route::post('/medicine-deletePharmacyOrder', 'DischargeController@deletePharmacyOrder')->name('discharge.deletePharmacyOrder');
    Route::get('/discharge-display-followup', 'DischargeController@displayFollowupForm')->name('discharge.display.followup');
    // Route::post('/saveHaemodialysis', 'HaemodialysisController@saveHaemodialysis')->name('saveHaemodialysis');

    // Route::get('/haemodialysis-opdsheet/{id}', 'HaemodialysisController@exportHaemoReport')->name('haemodialysis.opdsheet.generate');
    // Route::get('/reset-encounter', 'HaemodialysisController@resetEncounter')->name('reset.haemodialysis.encounter');
});
