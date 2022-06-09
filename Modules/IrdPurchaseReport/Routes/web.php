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

// Route::prefix('irdpurchasereport')->group(function() {
//     Route::get('/', 'IrdPurchaseReportController@index');
// });

Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'kharid'], function () {
    Route::any('/', 'IrdPurchaseReportController@index')->name('purchases.report');
    Route::any('/searchPurchaseDetail', 'IrdPurchaseReportController@searchPurchaseDetail')->name('searchPurchaseDetail');
    Route::get('/export-purchase-report', array(
        'as' => 'export.purchase.report',
        'uses' => 'IrdPurchaseReportController@exportPurchaseData'
    ));
    Route::get('/export-purchase-report-excel', array(
        'as' => 'export.purchase.report.excel',
        'uses' => 'IrdPurchaseReportController@exportPurchaseDataToExcel'
    ));
});
