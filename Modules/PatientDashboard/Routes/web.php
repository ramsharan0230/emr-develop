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

Route::group([
    'middleware' => ['web', 'patient-auth-checker'],
    'prefix' => 'patient-portal',
    'as' => 'patient.portal.'
], function () {
    Route::get('/dashboard', 'PatientDashboardController@index')->name('dashboard');

    Route::get('/profile', 'PatientDashboardController@profile')->name('profile');

    /*
     * laboratory
     */
    Route::get('/laboratory', 'PatientLaboratoryController@index')->name('laboratory');
    Route::get('/laboratory-report/{encounter}/{sample}', 'PatientLaboratoryController@labReport')->name('laboratory.report');
    Route::get('/laboratory-sample-list/{encounter}/{sample}', 'PatientLaboratoryController@listSampleData')->name('laboratory.sample.list');

    /*
     * Pharmacy
     */
    Route::any('/pharmacy', 'PharmacyController@index')->name('pharmacy');
    
    Route::get('/appoinment', 'AppoinmentController@index')->name('appoinment');
    Route::get('/prescription', 'PrescriptionController@index')->name('prescription');
    Route::get('/medical-history', 'MedicalHistoryController@index')->name('medical-history');

//immunization
    Route::get('/immunization', 'ImmunizationController@index')->name('immunization');

    // add document

    Route::get('/add_document', 'AdddocumentController@index')->name('add_document');
    // chat with doc
    Route::get('/chat', 'ChatWithDocController@index')->name('chat');

    //video conferencing

    Route::get('/video-conf', 'VideoconfController@index')->name('video-conf');

    // payment history
    Route::get('/payment-history', 'PaymentHistoryController@index')->name('payment-history');

    // consulation notes
    Route::get('/consulation-notes', 'ConsulationNotesController@index')->name('consulation-notes');

    // about us
    Route::get('/about-us', 'AboutUsController@index')->name('about-us');

});
