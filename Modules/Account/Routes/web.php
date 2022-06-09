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

Route::prefix('account')->group(function() {
    Route::get('/', 'AccountController@index');
});

Route::prefix('serviceitem')->group(function() {
    Route::get('/', 'ServiceController@index');
});

Route::group([
	'prefix' => 'account/laboratory',
], function() {
    Route::get('', 'LaboratoryController@index')->name('account.laboratory.index');
    Route::get('getSelectOptions', 'LaboratoryController@getSelectOptions');

	Route::post('saveUpdate', 'LaboratoryController@saveUpdate');
	Route::get('exportItems', 'LaboratoryController@exportItems')->name('account.laboratory.exportItems');
	Route::post('search', 'LaboratoryController@searchInDatabase')->name('account.laboratory.search');
});

Route::group([
	'prefix' => 'account/radiology',
], function() {
    Route::get('', 'RadiologyController@index')->name('account.radiology.index');
    Route::get('getSelectOptions', 'RadiologyController@getSelectOptions');

	Route::post('saveUpdate', 'RadiologyController@saveUpdate');
	Route::get('exportItems', 'RadiologyController@exportItems')->name('account.radiology.exportItems');
    Route::post('search', 'RadiologyController@searchInDatabase')->name('account.radiology.search');
});

Route::group([
	'prefix' => 'account/procedure',
], function() {
    Route::get('', 'ProcedureController@index')->name('account.procedure.index');
    Route::get('getSelectOptions', 'ProcedureController@getSelectOptions');

	Route::post('saveUpdate', 'ProcedureController@saveUpdate');
	Route::get('exportItems', 'ProcedureController@exportItems')->name('account.procedure.exportItems');
    Route::post('search', 'ProcedureController@searchInDatabase')->name('account.procedure.search');
});

Route::group([
	'prefix' => 'account/equipment',
], function() {
    Route::get('', 'EquipmentController@index')->name('account.equipment.index');
    Route::get('getSelectOptions', 'EquipmentController@getSelectOptions');

	Route::post('saveUpdate', 'EquipmentController@saveUpdate');
	Route::get('exportItems', 'EquipmentController@exportItems')->name('account.equipment.exportItems');
    Route::post('search', 'EquipmentController@searchInDatabase')->name('account.equipment.search');
});

Route::group([
	'prefix' => 'account/generalService',
], function() {
    Route::get('', 'GeneralServiceController@index')->name('account.generalService.index');
    Route::get('getSelectOptions', 'GeneralServiceController@getSelectOptions');

	Route::post('saveUpdate', 'GeneralServiceController@saveUpdate');
	Route::get('exportItems', 'GeneralServiceController@exportItems')->name('account.generalService.exportItems');
    Route::post('search', 'GeneralServiceController@searchInDatabase')->name('account.generalService.search');
});

Route::group([
	'prefix' => 'account/otheritem',
], function() {
    Route::get('', 'OtherItemController@index')->name('account.otheritem.index');
    Route::get('getSelectOptions', 'OtherItemController@getSelectOptions');

	Route::post('addVariable', 'OtherItemController@addVariable');
	Route::post('deleteVariable', 'OtherItemController@deleteVariable');
	Route::post('importVariable', 'OtherItemController@importVariable');

	Route::post('saveUpdate', 'OtherItemController@saveUpdate');
	Route::get('exportItems', 'OtherItemController@exportItems')->name('account.otheritem.exportItems');
	Route::post('search', 'OtherItemController@searchInDatabase')->name('account.otheritem.search');

	Route::get('getbillitemcode', 'OtherItemController@getbillitemcode')->name('account.otheritem.getbillitemcode');
});

Route::group([
	'prefix' => 'account/inventoryItem',
], function() {
    Route::get('', 'InventoryItemController@index')->name('account.inventoryItem.index');
	Route::get('getItems', 'InventoryItemController@getItems');
	Route::post('getMedicines', 'InventoryItemController@getMedicines');
	Route::get('getMedicineItem', 'InventoryItemController@getMedicineItem')->name('inventoryItem.medsearch');
	Route::get('getBrandName', 'InventoryItemController@getBrandName');

	Route::post('saveUpdate', 'InventoryItemController@saveUpdate');
	Route::post('delete', 'InventoryItemController@delete');
	Route::post('search', 'InventoryItemController@searchInDatabase')->name('inventoryItem.search');
	Route::get('savehiitem', 'InventoryItemController@savestockrateitem')->name('savestockrateitem');
	Route::get('deletehiitem', 'InventoryItemController@deletestockrateitem')->name('deletestockrateitem');
	Route::get('exportStockRate', 'InventoryItemController@exportstockrateitem')->name('exportstockrateitem');
});

Route::group([
	'prefix' => 'costing',
], function() {
	Route::group([
		'prefix' => 'datawise',
	], function() {
    	Route::get('extraprocedure', 'DatawiseController@index')->name('planreport.extraprocedure');
    	Route::get('majorprocedure', 'DatawiseController@index')->name('planreport.majorprocedure');
    	Route::get('radiologylist', 'DatawiseController@index')->name('planreport.radiologylist');

		Route::get('getPatientList', 'DatawiseController@getPatientList');
		Route::get('getPatientDetail', 'DatawiseController@getPatientDetail');
		Route::get('patientDetailReport', 'DatawiseController@patientDetailReport');
	});
});
