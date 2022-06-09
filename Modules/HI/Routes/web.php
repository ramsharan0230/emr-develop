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

Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'healthinsurance'], function () {
    Route::get('/claim', 'HIController@index')->name('claim-report');
    Route::get('/claim/claim-bill', 'HIController@claimbill')->name('claim-claim-bill');
    Route::get('/claim/claim-bills', 'HIController@claimbills')->name('claim-claim-bills');
    Route::get('/claim/view-bills', 'HIController@viewbills')->name('claim-view-bills');
    Route::get('/claim/nonins-bill', 'HIController@noninsbill')->name('claim-nonins-bill');
    Route::get('/claim/bill-upload', 'HIController@billupload')->name('claim-bill-upload');
    Route::get('/claim/bill-upload-status', 'HIController@billuploadstatus')->name('claim-bill-upload-status');
    Route::get('/claim/totalvsconsume', 'HIController@totalvsconsume')->name('hi-totalvsconsumed-report');
    Route::get('/totalvsconsume-report-pdf', 'HIController@totalvsconsumeexport')->name('hi-totalvsconsumed-report-pdf');
    Route::get('/totalvsconsume-report-excel', 'HIController@exportExcel')->name('hi-totalvsconsumed-report-excel');

    Route::get('/getDiagnosisByGroup', 'HIController@getDiagnosisByGroup')->name('getDiagnosisByGroup');
    Route::get('/getInitialDiagnosisCategoryAjaxclaim', 'HIController@getInitialDiagnosisCategoryAjax')->name('getInitialDiagnosisCategoryAjaxclaim');
    Route::get('/getDiagnosisByCodeclaim', 'HIController@getDiagnosisByCode')->name('getDiagnosisByCodeclaim');
    Route::post('/diagnosisStore', 'HIController@diagnosisStore')->name('diagnosisStoreclaim');
    Route::get('/getDiagnosisByCodeSearch', 'HIController@getDiagnosisByCodeSearch')->name('getDiagnosisByCodeSearchclaim');

});

