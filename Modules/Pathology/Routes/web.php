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

Route::group(['middleware' =>['web', 'auth-checker'], 'prefix' => 'pathology/'], function() {
	// Landing Pages
    Route::get('symptoms', 'PathologyController@getSymptoms')->name('get.view.symptoms');
    Route::get('syndromes', 'PathologyController@getSyndromes')->name('get.view.syndromes');
    // Syndromes Variables
    Route::post('syndromes/variable/insert', 'PathologyController@insertVariableSyndrome')->name('insert.variable.syndrome');
    Route::post('syndromes/variable/remove', 'PathologyController@deleteVariableSyndrome')->name('delete.variable.syndrome');
    Route::get('syndromes/variables/list', 'PathologyController@getVariableSyndrome');
    // syndromes
    Route::post('syndromes/insert', 'PathologyController@insertSundrome')->name('insert.syndrome');
    Route::post('syndromes/remove', 'PathologyController@deleteSundrome')->name('delete.syndrome');
    Route::get('syndromes/list', 'PathologyController@getSyndrome');

    // Symptoms Variables
    Route::post('symptoms/variable/insert', 'PathologyController@insertVariableSymptom')->name('insert.variable.symptom');
    Route::post('symptoms/variable/remove', 'PathologyController@deleteVariableSymptom')->name('delete.variable.symptom');
    Route::get('symptoms/variables/list', 'PathologyController@getVariableSymptom');
    // Symptoms
    Route::post('symptoms/insert', 'PathologyController@insertSymptom')->name('insert.symptoms');
    Route::post('symptoms/remove', 'PathologyController@deleteSymptom')->name('delete.symptoms');
    Route::post('symptoms/update', 'PathologyController@updateSymptom')->name('update.symptoms');
    Route::get('symptoms/list', 'PathologyController@getSymptom');
});