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
    'prefix' => 'icu/',
    'middleware' => ['web', 'auth-checker']
],function () {
    Route::any('/', 'ICUController@index')->name('icu');
    Route::post('/store', 'ICUController@store')->name('icu.store');
    /**
     * this route is for getting the diagnosis via ajax request
     */
    Route::get('/get_diagnosis', 'ICUController@getDiagnosis')->name('icu.getdiagnosis');
    Route::post('/get-diagnosis-list', 'ICUController@getInitialDiagnosisCategory')->name('icu.diagnosis-list');
    Route::post('/get-diagnosis-by-code', 'ICUController@getDiagnosisByCode')->name('icu.diagnosis-by-code');
    Route::post('/insert-diagnosis', 'ICUController@insertDiagnosis')->name('icu.store-diagnosis');
    Route::post('/remove-diagnosis', 'ICUController@removeDiagnosis')->name('icu.remove-diagnosis');

    /**
     * This route is for adding the drugs via ajax request
     */
    Route::post('/store_drug', 'ICUController@storeDrugActivity')->name('icu.store.drug');

    /**
     * search for auto complete
     */
    Route::get('/autocomplete', 'ICUController@autocomplete')->name('icu.autocomplete');

    /**
     * for removing the drug list from tblpatdosing
     */
    Route::post('/remove-drug', 'ICUController@removeDrug')->name('icu.remove-drug');

    /**
     * function for storing and fetching notes and messages from tblexamgeneral
     */
    Route::post('/store-notes', 'ICUController@storeNotes')->name('icu.store.notes');
    /**
     * function for storing and fetching notes and messages from tblexamgeneral
     */
    Route::post('/delete-notes/{id}', 'ICUController@deleteNotes')->name('icu.delete.notes');

    /** for adding endtime to fluid */

    Route::post('/stop-fluid', 'ICUController@StopFluid')->name('icu.stop.fluid');

    Route::get('/reset-encounter', 'ICUController@resetEncounter')->name('icu.reset.encounter');


});


Route::group([
    'prefix' => 'icu-general/',
    'middleware' => ['web', 'auth-checker'],
], function () {
    Route::any('/', 'IcuGeneralController@index')->name('icu-general');
    Route::any('/getDiagnosisByGroup', 'IcuGeneralController@getDiagnosisByGroup')->name('icu.general.getDiagnosisByGroups');
    Route::any('/getDiagnosisByCodes', 'IcuGeneralController@getDiagnosisByCodes')->name('icu.general.getDiagnosisByCodes');
    Route::get('/getInitialDiagnosisCategoryAjaxs', 'IcuGeneralController@getInitialDiagnosisCategoryAjaxs')->name('icu.general.getInitialDiagnosisCategoryAjaxs');
    Route::post('/diagnosisStoreIcu', 'IcuGeneralController@diagnosisStore')->name('icu.general.diagnosisStore');
    Route::get('/getDiagnosisByCodeSearch', 'IcuGeneralController@getDiagnosisByCodeSearch')->name('icu.general.getDiagnosisByCodeSearch');
    Route::post('/finalDiagnosisStore', 'IcuGeneralController@finalDiagnosisStore')->name('icu.general.finalDiagnosisStore');
    Route::post('/allergydrugstore', 'IcuGeneralController@insert_allergydrugstore')->name('icu.general.allergydrugstore');
    Route::get('/searchDrugs', 'IcuGeneralController@searchDrugs')->name('icu.general.searchDrugs');
    Route::get('/getAllDrugs', 'IcuGeneralController@getAllDrugs')->name('icu.general.getAllDrugs');
    Route::post('/store', 'IcuGeneralController@store')->name('icu.general.store');
    Route::post('/store-vital', 'IcuGeneralController@insertVitals')->name('icu.general.store.vitals');
    Route::post('/store-outputs', 'IcuGeneralController@insertOutputs')->name('icu.general.store.outputs');
    Route::post('/store-gcs', 'IcuGeneralController@insertGcsData')->name('icu.general.store.gcs');
    Route::post('/store-ventilator', 'IcuGeneralController@insertVentilatorData')->name('icu.general.store.ventilator');
    Route::post('/store-bollus', 'IcuGeneralController@insterbollus')->name('icu.general.store.bollus');
    Route::post('/store-intake', 'IcuGeneralController@insertIntake')->name('icu.general.store.intake');
    Route::get('/reset-encounter', 'IcuGeneralController@resetEncounter')->name('icu.general.reset.encounter');
});

Route::group([
    'prefix' => 'icu/notes/',
    'middleware' => ['web', 'auth-checker'],
], function () {
    Route::post('/insert', 'IcuNotesController@postInsertNotes')->name('icu.insert.note');
    Route::post('/update', 'IcuNotesController@postUpdateNotes')->name('icu.update.note');
    Route::get('/ajax-related-list', 'IcuNotesController@listOnSelect');
    Route::get('/ajax-date-list', 'IcuNotesController@listOnDate');
    Route::get('/ajax-list-all', 'IcuNotesController@listAll');
    Route::post('/refere-patient', 'IcuNotesController@postReferePatient')->name('icu.refere.patient');
});

