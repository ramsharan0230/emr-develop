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

Route::prefix('purchase')->group(function() {
    Route::get('/', 'PurchaseController@index');
    Route::get('/supplier-info', 'PurchaseController@supplierInfo')->name('supplier-info');
    // CRUD
    Route::post('/supplier-info/insert', 'PurchaseController@insertSupplierInfo')->name('insert.supplier.info');
    Route::post('/supplier-info/update', 'PurchaseController@updateSupplierInfo')->name('update.supplier.info');
    Route::post('/supplier-info/delete', 'PurchaseController@deleteSupplierInfo')->name('delete.supplier.info');
    Route::get('/supplier-info/changeStatus', 'PurchaseController@changeSupplierStatus')->name('change.supplier.status');
    Route::get('/supplier-info/search-supplier', 'PurchaseController@searchSupplier');
    Route::get('/supplier-info/get-supplier-info', 'PurchaseController@getSupplierInfo')->name('edit.supplier.info');
    Route::get('/supplier-info/get-all-suppliers-info', 'PurchaseController@getAllSupplierInfo');
    Route::get('/supplier-info/get-suppliers-detail/{supplier}', 'PurchaseController@getSupplierDetails');
    Route::get('/supplier-info/get-suppliers-detail-ajax/{supplier}', 'PurchaseController@getSupplierDetailsAjax');
    Route::any('/supplier-info/excel-export', 'PurchaseController@exportSupplierExcel')->name('export.supplier.info');
    // PDF
    Route::any('/supplier-info/export-all-supplier', 'PurchaseController@exportAllSupplier')->name('pdf.all.supplier');

    Route::group(['prefix' => 'fixed-asset', 'as' => 'fixedAsset'], function() {
        Route::get('', 'FixedAssetController@index')->name('index');

        Route::get('getAssetsEntry', 'FixedAssetController@getAssetsEntry')->name('getAssetsEntry');
        Route::post('saveAssetsEntry', 'FixedAssetController@saveAssetsEntry')->name('saveAssetsEntry');
        Route::post('updateAssetsEntry', 'FixedAssetController@updateAssetsEntry')->name('updateAssetsEntry');

        Route::get('/getItems', 'FixedAssetController@getItems')->name('getItems');
        Route::post('addItem', 'FixedAssetController@addItem')->name('addItem');
        Route::post('deleteItem', 'FixedAssetController@deleteItem')->name('deleteItem');
    });
});

Route::group([
    'middleware' => ['web', 'auth-checker'],
    'prefix' => 'purchaseentry',
], function() {
    Route::get('', 'PurchaseEntryController@index')->name('purchaseentry');
    Route::get('getRefrence', 'PurchaseEntryController@getRefrence');
    Route::get('getMedicineList', 'PurchaseEntryController@getMedicineList');
    Route::get('getPendingOpeningStocks', 'PurchaseEntryController@getPendingOpeningStocks');
    Route::get('getPendingPurchaseByRefNo', 'PurchaseEntryController@getPendingPurchaseByRefNo');
    Route::post('save', 'PurchaseEntryController@save');

    Route::post('finalSave', 'PurchaseEntryController@finalSave')->name('purchaseentry.finalSave');
    Route::get('export', 'PurchaseEntryController@export')->name('purchaseentry.export');
    Route::get('excel/export', 'PurchaseEntryController@exportPurchaseBillExcel')->name('purchaseentry.excel.export');
    Route::get('check-batch', 'PurchaseEntryController@checkBatchExpiry')->name('purchaseentry.checkBatch');
    Route::post('delete', 'PurchaseEntryController@delete');

    Route::get('download-excel-format','PurchaseEntryController@downloadExcelFormat')->name('download.excel.format');
    Route::post('import-purchase-entry','PurchaseEntryController@importPurchaseEntry')->name('import.purchase.entry');
});

Route::group([
    'middleware' => ['web', 'auth-checker'],
    'prefix' => 'billing/purchaseorder',
], function() {
    Route::get('', 'PurchaseOrderController@index')->name('billing.purchaseorder');
    Route::post('finalsave', 'PurchaseOrderController@finalsave');

    Route::get('printBill', 'PurchaseOrderController@printBill');
    Route::get('getQuotationNoOrders', 'PurchaseOrderController@getQuotationNoOrders');
});


//Routes added by anish for Purchase Return

Route::group([
    'middleware' => ['web', 'auth-checker'],
    'prefix' => 'purchase-return',
], function() {
    Route::get('/', 'PurchaseReturnController@index')->name('purchase.return');
    Route::get('/reference', 'PurchaseReturnController@getRefrence')->name('purchase.return.reference');
    Route::get('/medicine', 'PurchaseReturnController@getMedicine')->name('purchase.return.medicine');
    Route::get('/medicine-with-reference', 'PurchaseReturnController@getMedicineWithReference')->name('purchase.return.medicineWithReference');
    Route::get('/batch', 'PurchaseReturnController@getBatch')->name('purchase.return.batch');
    Route::get('/expiry', 'PurchaseReturnController@getExpiry')->name('purchase.return.expiry');
    Route::post('/insertStockReturn', 'PurchaseReturnController@insertStockReturn')->name('purchase.return.insertStockReturn');
    Route::post('/finalsave', 'PurchaseReturnController@finalSave')->name('purchase.return.finalsave');
    Route::get('/export-report', 'PurchaseReturnController@exportReport')->name('purchase.return.export.report');
    Route::post('/delete', 'PurchaseReturnController@deleteEntry')->name('purchase.return.deleteEntry');
    Route::get('/getPendingStockReturns', 'PurchaseReturnController@getPendingStockReturns')->name('purchase.return.getPendingStockReturns');
});
