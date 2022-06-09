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

Route::group(['middleware' => ['web' => 'auth-checker'], 'prefix' => 'pharmacist'],function() {
    Route::get('/', 'PharmacistController@index');

    // Surgical
    Route::prefix('/surgical')->group(function() {
        Route::get('/', 'SurgicalController@surgical')->name('surgical');

        // surgical-variable
        Route::post('/insert-surgical-variable', 'SurgicalController@insertSurgicalVariable')->name('insert.surgical.name.variable');
        Route::post('/delete-surgical-variable', 'SurgicalController@deleteSurgicalVariable')->name('delete.surgical.name.variable');
        Route::get('/get-all-surgical-variables', 'SurgicalController@getSurgicalVariables');
        Route::get('/surgical-names-info', 'SurgicalController@getSurgicalNameinfo')->name('surgicalNameInfo');
        // surgical-type
        Route::post('/insert-surgical-type', 'SurgicalController@insertSurgicalType')->name('insert.surgical.name.type');
        Route::post('/delete-surgical-type', 'SurgicalController@deleteSurgicalType')->name('delete.surgical.name.type');
        Route::get('/get-all-surgical-types', 'SurgicalController@getSergicalTypes');
        // Surgical CRUD
        Route::post('/insert-surgical', 'SurgicalController@insertSurgical')->name('insert.surgical.data');
        Route::post('/update-surgical', 'SurgicalController@updateSurgical')->name('update.surgical.data');
        Route::post('/delete-surgical', 'SurgicalController@deleteSurgical')->name('delete.surgical.data');
        Route::get('/get-clicked-surgical-data', 'SurgicalController@getSingleSurgicalData')->name('get.surgical.data');
        // Surgical Brand CRUD
        Route::post('/brand/insert-surg-brand', 'SurgicalController@insertSurgicalBrand')->name('insert.surg.brand');
        Route::post('/brand/update-surg-brand', 'SurgicalController@updateSurgicalBrand')->name('update.surg.brand');
        Route::post('/brand/delete-surg-brand', 'SurgicalController@deleteSurgicalBrand')->name('delete.surg.brand');
        Route::get('/get-surgical-brand-data', 'SurgicalController@getSurgicalBrandData')->name('get.surgical-brand.data');
    });

    // Extra Item
    Route::prefix('/extra-item')->group(function() {
    	Route::get('/', 'PharmacistController@extraItem')->name('extra-item');
        Route::get('/searchExtraitem', 'PharmacistController@searchExtraitem')->name('searchExtraitem');
	    Route::get('/get-brand-details', 'PharmacistController@getBrandDetails')->name('getBrandDetails');
        Route::get('/sidebar-item-list', 'PharmacistController@getSidebarItem');
        Route::get('/sidebar-brand-list', 'PharmacistController@getSidebarBrand');

	    // extra-item-variables
	    Route::post('/insert-variables', 'PharmacistController@insertVariable')->name('insert.item.name.variable');
	    Route::post('/delete-variables', 'PharmacistController@deleteVariable')->name('delete.item.name.variable');
        Route::get('/get-all-variables', 'PharmacistController@getVariables');
        Route::get('/extra-items-info', 'PharmacistController@getItemNameinfo')->name('itemNameInfo');
        // CRUD Extra Item
        Route::post('/insert-extra-item', 'PharmacistController@insertExtraItem')->name('insert.extra.item');
        Route::post('/delete-extra-item', 'PharmacistController@deleteExtraItem')->name('delete.extra.item');
    });
});


//   route by anish

Route::group(['middleware' =>['web', 'auth-checker'], 'prefix' => 'pharmacist', 'as' => 'pharmacist.'], function() {

    Route::group(['prefix' => 'labelling', 'as' => 'labelling.'], function() {
        Route::get('/', 'ActivityController@labelling')->name('index');
        Route::post('/getvolunitmedbrand', 'ActivityController@getVolunitfromBrand')->name('getvolunitmedbrand');
        Route::post('/getByLabelType', 'ActivityController@getByLabelType')->name('getByLabelType');
        Route::post('/addlocallabels', 'ActivityController@addLocalLabels')->name('addlocallabels');
        Route::get('/editlocallabels/{fldid}', 'ActivityController@editLocalLabels')->name('editlocallabels');
        Route::post('/updatelocallabels/{fldid}', 'ActivityController@updateLocalLabels')->name('updatelocallabels');
        Route::delete('/deletelocallabels/{fldid}', 'ActivityController@deleteLocalLabels')->name('deletelocallabels');
        Route::get('/exportalllabellingtopdf', 'ActivityController@exportAllLabellingToPdf')->name('exportalllabellingtopdf');
    });

    Route::group(['prefix' => 'activation',  'as' => 'activation.'], function() {
        Route::get('/', 'ActivityController@activation')->name('index');
        Route::get('/getmedbrands', 'ActivityController@getMedbrands')->name('medicines');
        Route::get('/getsurgbrands', 'ActivityController@getSurgbrands')->name('surgical');
        Route::get('/getextrabrands', 'ActivityController@getExtraBrands')->name('extra');
        Route::post('/enabledisableall', 'ActivityController@enableDisableAll')->name('enabledisableall');
        Route::post('/togglestatus', 'ActivityController@toggleStatus')->name('togglestatus');
    });


    Route::group(['prefix' => 'protocols',  'as' => 'protocols.'], function() {
        Route::get('/', 'ProtocolController@protocols')->name('index');
        Route::post('/addmedgroup', 'ProtocolController@addMedGroup')->name('addmedgroup');
        Route::delete('/deletemedgroup/{fldid}', 'ProtocolController@deleteMedgroup')->name('deletemedgroup');
        Route::post('/medicinesfromroute', 'ProtocolController@getMedicinesFromFldroute')->name('getmedicinesfromfldroute');
        Route::post('/addproductgroup', 'ProtocolController@addProductGroup')->name('addproductgroup');
        Route::post('/loadmedicinegrouping', 'ProtocolController@loadproductMedGroup')->name('loadmedicinegrouping');
        Route::get('/exporttopdfallmedicinelist', 'ProtocolController@exportToPdfAll')->name('list');
        Route::get('/exportmedicinemedgroup/{fldmedgroup}', 'ProtocolController@exportMedicineMedgroup')->name('export');
        Route::delete('/deleteproductgroup/{fldid}', 'ProtocolController@DeleteProductGroup')->name('deleteproductgroup');
    });

    Route::group(['prefix' => 'outoforder',  'as' => 'outoforder.'], function() {
        Route::get('/', 'OutoforderController@outOfOrder')->name('index');
        Route::post('/medicinesfromroute', 'OutoforderController@getMedicinesFromFldroute')->name('getmedicinesfromfldroute');
        Route::post('/loadentries', 'OutoforderController@loadEntries')->name('loadentries');
        Route::post('/populateentryforupdate', 'OutoforderController@populateEntryForUpdate')->name('populateentryforupdate');
        Route::post('/updateentry', 'OutoforderController@updateEntry')->name('updateentry');
    });

    Route::group(['prefix' => 'inventory', 'as' => 'inventory.'], function() {
        Route::get('/', 'InventoryController@index')->name('inventory');

    });

    Route::group(['prefix' => 'pharmacy-sales-book', 'as' => 'pharmacy-sales.'], function() {
        Route::get('/', 'PharmacySalesBookConrollerController@index')->name('index');
        Route::get('/search', 'PharmacySalesBookConrollerController@searchData')->name('search');
        Route::get('/export-pdf', 'PharmacySalesBookConrollerController@exportPdf')->name('export.pdf');
        Route::get('/export-excel', 'PharmacySalesBookConrollerController@exportExcel')->name('export.excel');

    });


});

//routes by anish end
