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

Route::prefix('inventory')->group(function() {
    Route::get('/purchase-entry', 'InventoryController@purchaseEntry')->name('purchase-entry');
//    Route::get('/stock-transfer', 'InventoryController@stockTransfer')->name('stock-transfer');
    Route::get('/stock-return', 'InventoryController@stockReturn')->name('stock-return');
//    Route::get('/stock-consume', 'InventoryController@stockconsume')->name('stock-consume');
    Route::get('/stock-adjustment', 'InventoryController@stockAdjustment')->name('stock-adjustment');

    Route::get('/expirydate-report', 'ReportController@expiryReport')->name('expirydate-report');
    Route::get('/under-stock-report', 'ReportController@underStockReport')->name('under-stock-report');
    Route::get('/purchase-vat-report', 'VATReportController@index')->name('purchase-vat-report');
    Route::get('/purchase-vat-report-pdf', 'VATReportController@export')->name('purchase-vat-report-pdf');
});
