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
Route::group(['middleware' =>['web', 'auth-checker'], 'prefix'=> 'mapping'], function() {

    Route::get('/', 'HMISMappingController@index')->name('mapping');

    Route::get('/emergency', 'HMISMappingController@emergencyOptions')->name('emergency_options');
    Route::get('/inpatients', 'HMISMappingController@inPatientOptions')->name('inpatients_options');
    Route::get('/diagnostic', 'HMISMappingController@diagnosticService')->name('diagnostic_options');
    Route::get('/delivery', 'HMISMappingController@delivery')->name('delivery_options');
    Route::post('/laboratory', 'HMISMappingController@laboratory')->name('laboratory_options');
    Route::get('/culture', 'HMISMappingController@culture')->name('culture_options');
    Route::get('/culture_specimen', 'HMISMappingController@cultureSpecimen')->name('culture_specimen');
    Route::get('/free_service', 'HMISMappingController@freeService')->name('free_service');
    Route::post('/save_mappings', 'HMISMappingController@save_mappings')->name('save_mappings');
    Route::post('/save_test', 'HMISMappingController@saveTest')->name('save_test');
    Route::get('/mapping_report', 'HMISMappingController@mappingReport')->name('mapping.report');
    Route::get('/delete/{id}', 'HMISMappingController@delete')->name('mapping.delete');

});
