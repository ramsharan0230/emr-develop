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

Route::prefix('eappointment')->group(function() {
    Route::any('/', 'EappointmentController@index')->name('eappointment-list');
    Route::any('/appointment-log', 'EappointmentController@revenueReport')->name('eappointment-log');
    Route::post('appointment-checkedin', 'EappointmentController@checkin')->name('eappointment.checkedin');
    Route::any('/save-patient', 'EappointmentController@savePatient')->name('eappointment-savepatient');
   
    Route::any('/doctor-duty-roster', 'DoctorDutyRosterController@doctorDutyRoster')->name('eappointment-ddr');
    Route::any('/get-doctor-specialization', 'DoctorDutyRosterController@getSpecializationDoctor')->name('eappointment-doctor-specialization');
    Route::any('/add-doctor-duty-roster', 'DoctorDutyRosterController@addDoctorDutyRoster')->name('eappointment-doctor-add-ddr');
    Route::any('/get-doctor-duty-roster', 'DoctorDutyRosterController@getDoctorDutyRoster')->name('eappointment-doctor-get-ddr');
    Route::any('/delete-doctor-duty-roster', 'DoctorDutyRosterController@deleteDoctorDutyRoster')->name('eappointment-doctor-delete-ddr');

    
  
    Route::any('/service-setup', 'ServiceSetupController@index')->name('eappointment-service-setup');
    Route::post('/add-service-setup', 'ServiceSetupController@addService')->name('eappointment-add-service-setup');
    Route::post('/delete-service-setup', 'ServiceSetupController@deleteService')->name('eappointment-delete-service-setup');
    Route::post('/edit-service-setup', 'ServiceSetupController@editService')->name('eappointment-edit-service-setup');
    Route::post('/update-service-setup', 'ServiceSetupController@updateService')->name('eappointment-update-service-setup');
    Route::any('/get-service-billing-mode', 'ServiceSetupController@getServiceBillingMode')->name('eappointment-get-service-billing-mode');



    Route::any('/doctor-setup', 'DoctorController@doctorSetup')->name('eappointment-doctor-setup');
    Route::any('/doctor-add', 'DoctorController@doctorAdd')->name('eappointment-doctor-add');
    Route::any('/doctor', 'DoctorController@getEappointmentDoctors')->name('eappointment-doctor-list');
    Route::any('/doctor-view', 'DoctorController@doctorView')->name('eappointment-doctor-view');
    Route::any('/doctor-edit/{id}', 'DoctorController@doctorEdit')->name('eappointment-doctor-edit');
    Route::any('/doctor-update/{id}', 'DoctorController@doctorUpdate')->name('eappointment-doctor-update');
});
