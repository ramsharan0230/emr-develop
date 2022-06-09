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

Route::prefix('patientregister')->group(function() {
    Route::get('/', 'PatientregisterController@index')->name('patient.register');
    Route::post('/getencounter', 'PatientregisterController@get_encounter_number')->name('patient.get_encounter');
    Route::post('/getdetails', 'PatientregisterController@getDetails')->name('patient.getDetails');
});
