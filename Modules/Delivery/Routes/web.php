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

Route::prefix('delivery')->group(function() {
    Route::any('/', 'DeliveryController@index')->name('delivery');
    Route::get('/reset-encounter', 'DeliveryController@resetEncounter')->name('delivery.reset.encounter');

    
    Route::post('/save_height', 'DeliveryController@save_height')->name('delivery.save.height');
    Route::post('/save_weight', 'DeliveryController@save_weight')->name('delivery.save.weight');
    Route::post('/getAgeurl', 'DeliveryController@getAgeurl')->name('delivery.save.getAgeurl');


    Route::post('/get_encounter_number', 'DeliveryController@get_encounter_number')->name('delivery.get_encounter_number');

    Route::post('/save_consultant', 'DeliveryController@save_consultant')->name('delivery.save_consultant');
   
    
    Route::group([
    	'prefix' => 'newdelivery'
    ], function() {
        Route::get('getDelivery', 'NewDeliveryController@getDelivery');
    	Route::post('store', 'NewDeliveryController@store');
        Route::post('update', 'NewDeliveryController@update');

        Route::get('getSelectOptions', 'NewDeliveryController@getSelectOptions');
        Route::post('addVariable', 'NewDeliveryController@addVariable');
    	Route::post('deleteVariable', 'NewDeliveryController@deleteVariable');

        Route::post('saveUpdateChildGender', 'NewDeliveryController@saveUpdateChildGender');
    });

    Route::group([
        'prefix' => 'deliveryexamination',
    ], function() {
        Route::get('getExaminationLists', 'DeliverExaminationController@getExaminationLists');
        Route::get('getModalContent', 'DeliverExaminationController@getModalContent');
        Route::get('getPatientExaminations', 'DeliverExaminationController@getPatientExaminations');

        Route::post('savePatientExaminations', 'DeliverExaminationController@savePatientExaminations');

        Route::get('geerateReport', 'DeliverExaminationController@geerateReport');
    });

    Route::group([
        'prefix' => 'newborn',
    ], function() {
        Route::get('getExaminations', 'NewBornController@getExaminations');
        Route::get('getChildren', 'NewBornController@getChildren');
        Route::get('getChildData', 'NewBornController@getChildData');

        Route::get('getModalContent', 'NewBornController@getModalContent');

        Route::post('changedob', 'NewBornController@changedob');
        Route::post('addExamination', 'NewBornController@addExamination');

        Route::get('examReport', 'NewBornController@examReport');
        Route::get('birthcertificate', 'NewBornController@birthcertificate');
    });

    Route::group([
        'prefix' => 'pharmacy',
    ], function() {
        Route::get('getAllMedicine', 'DeliveryController@getAllMedicine');
        Route::get('getChildren', 'DeliveryController@getChildren');
    });


    Route::get('/delivery-report/{id}', 'DeliveryController@deliveryReport')->name('dataview.menu.delivery');

});
