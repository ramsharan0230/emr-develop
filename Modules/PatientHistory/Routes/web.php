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

Route::group(['middleware' =>['web', 'auth-checker'], 'prefix' => 'patient-history'], function() {
    Route::any('/', 'PatientHistoryController@index')->name('patient-history');
    Route::get('/encounter-history/{encounterId}', 'PatientEncounterController@displayEncounterHistory')->name('encounter.history');
    Route::any('/patient-data/{encounter}', 'PatientEncounterController@showEncounterDetails')->name('history.by.patient');

    Route::post('/patient-history-transition', 'EncounterFunctionsController@transition')->name('patient.history.transition');
    Route::post('/patient-history-symptoms', 'EncounterFunctionsController@symptoms')->name('patient.history.symptoms');
    Route::post('/patient-history-foods', 'EncounterFunctionsController@foods')->name('patient.history.foods');
    Route::post('/patient-history-exam', 'EncounterFunctionsController@exam')->name('patient.history.exam');
    Route::post('/patient-history-laboratory', 'EncounterFunctionsController@laboratory')->name('patient.history.laboratory');
    Route::post('/patient-history-radiology', 'EncounterFunctionsController@radiology')->name('patient.history.radiology');
    Route::post('/patient-history-notes', 'EncounterFunctionsController@notes')->name('patient.history.notes');
    Route::post('/patient-history-medDosing', 'EncounterFunctionsController@medDosing')->name('patient.history.medDosing');
    Route::post('/patient-history-progress', 'EncounterFunctionsController@progress')->name('patient.history.progress');
    Route::post('/patient-history-nursActivity', 'EncounterFunctionsController@nursActivity')->name('patient.history.nursActivity');
    Route::post('/patient-history-bladder', 'EncounterFunctionsController@bladder')->name('patient.history.bladder');
});
