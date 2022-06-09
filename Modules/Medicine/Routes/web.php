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


Route::group(['middleware' =>['web', 'auth-checker'], 'prefix' => 'medicines', 'as' => 'medicines.'], function() {


    Route::post('/addgenericname', 'MedicineController@addGenericName')->name('addgeneric');
    Route::delete('/deletegeneric/{fldcodename}', 'MedicineController@deleteGeneric')->name('deletegeneric');
    Route::post('/genericnamefilter', 'MedicineController@genericNameFilter')->name('genericnamefilter');

    Route::post('/addmedcategory', 'MedicineController@addMedCategory')->name('addmedcategory');
    Route::delete('/deletecategory/{fldid}', 'MedicineController@deleteMedCategory')->name('deletemedcategory');
    Route::post('/medcategorynamefilter', 'MedicineController@medcategoryNameFilter')->name('medcategorynamefilter');

    Route::post('/addchemicals', 'MedicineController@addChemicals')->name('addchemicals');
    Route::delete('/deletechemicals/{fldid}', 'MedicineController@deleteChemicals')->name('deletechecmials');
    Route::post('/chemicalnamefilter', 'MedicineController@chemicalNameFilter')->name('chemicalnamefilter');

    Route::post('/addsensitivity', 'MedicineController@addSensitivity')->name('addsensitivity');
    Route::delete('/deletesensitivity/{fldid}', 'MedicineController@deleteSensitivity')->name('deletesensitivity');
    Route::post('/sensitivitynamefilter', 'MedicineController@sensitivityNameFilter')->name('sensitivitynamefilter');

    Route::post('/adddosageform', 'MedicineController@addDosageForm')->name('adddosageform');
    Route::delete('/deletedosageform/{fldid}', 'MedicineController@deleteDosageForm')->name('deletedosageform');
    Route::post('/dosagenamefilter', 'MedicineController@dosageNameFilter')->name('dosagenamefilter');


    Route::post('/listing/search', 'MedicineController@searchMedicine')->name('listing.search');

    Route::group(['prefix' => 'genericinfo', 'as' => 'generic.'], function() {
        Route::get('/', 'GenericInfoController@index')->name('list');
        Route::get('/searchGenericinfo', 'GenericInfoController@searchGenericinfo')->name('searchGenericinfo');
        Route::post('/add', 'GenericInfoController@addGenericInfo')->name('add');
        Route::get('/edit/{fldcodename}', 'GenericInfoController@editGenericInfo')->name('edit');
        Route::patch('/update/{fldcodename}', 'GenericInfoController@updateGenericInfo')->name('update');
        Route::delete('/delete/{fldcodename}', 'GenericInfoController@deleteGenericInfo')->name('delete');
    });

    Route::group(['prefix' => 'medicineinfo', 'as' => 'medicineinfo.'], function() {

        Route::get('/', 'MedicineinfoController@index')->name('list');
        Route::get('/test', 'MedicineinfoController@indexTest')->name('list.test');
        Route::get('/get-medicine-by-generic', 'MedicineinfoController@getMedicineByGeneric')->name('by.generic');
        Route::post('/adddrug', 'MedicineinfoController@addDrug')->name('adddrug');
        Route::get('/drug-detail', 'MedicineinfoController@getMedicineDetails')->name('drug.details');
        Route::delete('/deletedrug', 'MedicineinfoController@deleteDrug')->name('deletedrug');

        Route::post('/addlabels', 'MedicineinfoController@addLabels')->name('addlabel');
        // Route::delete('/deletelabels/{flddrug}/{fldlabel}', 'MedicineinfoController@deleteLabels')->name('deletelabel');
        Route::get('/brand-detail', 'MedicineinfoController@getBrandDetails')->name('brand.details');
        Route::post('/addbrandinfo', 'MedicineinfoController@addBrandInfo')->name('addbrandinfo');
        Route::delete('/deletebrandinfo', 'MedicineinfoController@deleteBrandInfo')->name('deletebrandinfo');
    });

});
