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
    'middleware' => ['web', 'auth-checker'],
    'prefix' => 'admin/discount/mode',
    'as' => 'patient.discount.mode.'
], function () {
    Route::get('/', array(
        // 'middleware' => 'can:view,App\CogentUsers',
        'as' => 'form',
        'uses' => 'PatientDiscountModeController@displayDiscountModeForm'
    ));

    Route::get('/insert-patient-mode', array(
        'as' => 'insert',
        'uses' => 'PatientDiscountModeController@insertPatientMode'
    ));

    Route::get('/edit-patient-mode', array(
        'as' => 'edit',
        'uses' => 'PatientDiscountModeController@editPatientMode'
    ));

    Route::post('/update-patient-mode', array(
        'as' => 'update',
        'uses' => 'PatientDiscountModeController@updatePatientMode'
    ));

    Route::any('/list-discount-items', array(
        'as' => 'list.items.by.group',
        'uses' => 'PatientDiscountModeController@listByDiscountGroup'
    ));

    Route::post('/add-discount-items', array(
        'as' => 'add.items.by.group',
        'uses' => 'PatientDiscountModeController@addByDiscountGroup'
    ));

    Route::post('/remove-discount-items', array(
        'as' => 'remove.items.by.group',
        'uses' => 'PatientDiscountModeController@removeByDiscountGroup'
    ));

    Route::post('/update-discount-items', array(
        'as' => 'update.items.by.group',
        'uses' => 'PatientDiscountModeController@updateByDiscountGroup'
    ));

    Route::post('/delete-discount-items', array(
        'as' => 'delete.items',
        'uses' => 'PatientDiscountModeController@deleteNoDiscount'
    ));

    Route::post('/delete-discount-mode', array(
        'as' => 'delete.mode',
        'uses' => 'PatientDiscountModeController@deleteDiscountMode'
    ));

    Route::post('/custom-discount-list', array(
        'as' => 'custom.list',
        'uses' => 'PatientDiscountModeController@displayDiscountList'
    ));

    Route::post('/custom-discount-type-list', array(
        'as' => 'custom.type.list',
        'uses' => 'PatientDiscountModeController@displayDiscountListByType'
    ));

    Route::post('/custom-discount-save', array(
        'as' => 'custom.type.save',
        'uses' => 'PatientDiscountModeController@saveCustomDiscount'
    ));

    Route::post('/custom-discount-delete', array(
        'as' => 'delete.custom.discount.by.type',
        'uses' => 'PatientDiscountModeController@deleteCustomDiscountByType'
    ));

    Route::post('/custom-discount-specific-save', array(
        'as' => 'specific.custom.discount.save',
        'uses' => 'PatientDiscountModeController@saveCustomDiscountSpecific'
    ));

    Route::get('/export-excel-patient-mode', array(
        'as' => 'export.excel',
        'uses' => 'PatientDiscountModeExportReportController@exportToExcel'
    ));

    Route::get('/export-pdf-patient-mode', array(
        'as' => 'export.pdf',
        'uses' => 'PatientDiscountModeExportReportController@exportToExcel'
    ));

    Route::get('/export-pdf-patient-mode', array(
        'as' => 'export.pdf',
        'uses' => 'PatientDiscountModeExportReportController@exportToPdf'
    ));



});
