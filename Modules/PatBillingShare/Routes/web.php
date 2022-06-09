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

Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'pat-billing-share'], function () {

    Route::get('/', 'PatBillingShareController@index')->name('pat-billing-share.index');
    Route::get('/pdf', 'PatBillingShareController@exportData')->name('pat-billing-share.pdf');
    Route::get('/doctorwiseShareReportSummary', 'PatBillingShareController@newdoctorwiseShareReportsummary')->name('pat-billing-share.doctorsummary');

    // Route::get('/filter', 'PatBillingShareController@filter')->name('pat-billing-share.filter');
    Route::get('/checkUsername', 'PatBillingShareController@checkUsername')->name('pat-billing-share.checkUsername');
    Route::get('/doctorwiseShareReport', 'PatBillingShareController@doctorwiseShareReport')->name('doctorwiseShareReport');
    Route::get('/doctorwiseShareReportPatient', 'PatBillingShareController@doctorwiseShareReportPatient')->name('doctorwiseShareReportPatient');

    Route::get('/export-detailreport', 'PatBillingShareController@getReportDetail')->name('pat-getReportDetail');
    //Excel routes
    Route::get('/export-excell', 'PatBillingShareController@exportExcell')->name('pat-billing-share.export.excel');
    Route::get('/export-referral-doctor', 'PatBillingShareController@generateReferalDoctorListExcell')->name('pat-billing-share.export.referral.doctor.list');
    Route::get('/doctor-wise-referral-excel', 'PatBillingShareController@doctorWiseReferralExcelExport')->name('pat-billing-share.export.referral.doctor.wise');
    Route::get('/doctor-wise-patient-excel', 'PatBillingShareController@doctorWiseShareReportPatientExport')->name('pat-billing-share.export.doctor.wise.patient');
    Route::get('/doctor-wise-without-referral-excel', 'PatBillingShareController@doctorWiseShareWithoutReferalExcel')->name('pat-billing-share.export.doctor.wise.without.referal');
    Route::get('/doctor-wise-without-referral-patient-excel', 'PatBillingShareController@doctorWiseShareWithoutReferalPatientExcel')->name('pat-billing-share.export.doctor.wise.patient.without.referal');
    Route::get('/doctorshareexcel', 'PatBillingShareController@doctorreportshare')->name('pat-doctorreportshare');
});

Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'fraction-payment'], function () {
    Route::get('/', 'FractionPaymentController@index')->name('fraction-payment.index');
    Route::get('/get-bill-details', 'FractionPaymentController@getBillDetails')->name('fraction-payment.bill.details');
    Route::post('/update-doctor-share', 'FractionPaymentController@updateDoctorShare')->name('fraction-payment.update-doctor-share');
});
Route::get('/cronreturnedate', 'PatBillingShareController@cronreturnedate')->name('cronreturnedate');
Route::get('/emergencyShareUpdate', 'PatBillingShareController@emergencyShareUpdate')->name('emergencyShareUpdate');



