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

Route::prefix('neuro')->middleware(['web', 'auth-checker'])->group(function() {
    Route::any('/', 'NeuroController@index')->name('neuro');

    /**
     * search for auto complete
     */
    Route::get('/autocomplete', 'NeuroController@autocomplete')->name('autocomplete');

    /**
     * CREATE NEW RECORD
     */
//Route::get('/create/{encounter_no}', 'NeuroController@create')->name('create')->middleware('auth-checker');
    Route::post('/store', 'NeuroController@store')->name('store');
    Route::get('/edit/{encounter_no}', 'NeuroController@edit')->name('edit');
    Route::post('/update/{encounter_no}', 'NeuroController@update')->name('update');
    Route::post('/delete/{encounter_no}', 'NeuroController@delete')->name('delete');

    /**
     * This route is for adding the drugs via ajax request
     */
    Route::post('/store_drug', 'NeuroController@storeDrugActivity')->name('store.drug');
    /**
     * this route is for getting the diagnosis via ajax request
     */
    Route::get('/get_diagnosis', 'NeuroController@getDiagnosis')->name('getdiagnosis');

    Route::post('/get-diagnosis-list', 'NeuroController@getInitialDiagnosisCategory')->name('diagnosis-list');
    Route::post('/get-diagnosis-by-code', 'NeuroController@getDiagnosisByCode')->name('diagnosis-by-code');
    Route::post('/insert-diagnosis', 'NeuroController@insertDiagnosis')->name('store-diagnosis');
    /**
     * for removing the drug list from tblpatdosing
     */
    Route::post('/remove-drug', 'NeuroController@removeDrug')->name('remove-drug');

    /**
     * for removing the drug list from tblpatdosing
     */
    Route::post('/remove-diagnosis', 'NeuroController@removeDiagnosis')->name('remove-diagnosis');

    /**
     * function for storing and fetching notes and messages from tblexamgeneral
     */
    Route::post('/store-notes', 'NeuroController@storeNotes')->name('store.notes');
    /**
     * function for storing and fetching notes and messages from tblexamgeneral
     */
    Route::post('/delete-notes/{id}', 'NeuroController@deleteNotes')->name('delete.notes');

    /** for adding endtime to fluid */

    Route::post('/stop-fluid', 'NeuroController@StopFluid')->name('stop.fluid');

    /** routes for creating PDF */
    Route::get('/generate-report/{encounter_no}', 'NeuroController@generateReport')->name('icu.generate.report');

    Route::get('/reset-encounter', 'NeuroController@resetEncounter')->name('reset.neuro.encounter');

});

/** Routes of OPD Neuro */
Route::prefix('opdneuro')->middleware(['web', 'auth-checker'])->group(function() {
    Route::any('/create', 'OpdNeuroController@index')->name('opdneuro');
    Route::any('/', 'OpdNeuroController@home')->name('opdneuro.home');
    Route::post('/store', 'OpdNeuroController@store')->name('opdneuro.store');
    Route::get('/reset-encounter', 'OpdNeuroController@resetEncounter')->name('reset.opdneuro.encounter');

});
