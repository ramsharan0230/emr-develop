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
    'prefix' => 'store',
    'as' => 'store.'
], function () {
    Route::get('/', 'StoreController@getStore');
    // CRUD VARIABLE
    Route::post('/target/insert-variable', 'StoreController@insertTargetVariable')->name('insert.target.variable');
    Route::post('/target/delete-variable', 'StoreController@deleteTargetVariable')->name('delete.target.variable');
    Route::get('/target/get-variable', 'StoreController@getTargetVariable');
    // Med
    Route::get('/tblentry/get-med', 'StoreController@getMed');

    // Inventory DB routes
    Route::group([
        'prefix' => 'inventorydb',
    ], function () {
        Route::match(['get', 'post'], '/', 'InventoryDbController@index')->name('inventorydb.index');
        Route::get('/export', 'InventoryDbController@export')->name('inventorydb.export');
        Route::get('/inventory', 'InventoryDbController@inventory')->name('inventorydb.inventory');
    });

    Route::group([
        'prefix' => 'storeCoding',
    ], function () {
        Route::get('', 'StoreCoddingController@index')->name('storagecode');
        Route::get('getMedicines', 'StoreCoddingController@getMedicines');
        Route::post('update', 'StoreCoddingController@update');
    });

    Route::group([
        'prefix' => 'purchaseorder',
    ], function () {
        Route::get('', 'PurchaseOrderController@index')->name('purchaseorder');
        Route::get('getRefrence', 'PurchaseOrderController@getRefrence');
        Route::get('getLocation', 'PurchaseOrderController@getLocation');

        Route::get('getMedicineList', 'PurchaseOrderController@getMedicineList');
        Route::get('getMedicineDetail', 'PurchaseOrderController@getMedicineDetail');

        Route::post('saveOrder', 'PurchaseOrderController@saveOrder');
        Route::get('finalupdate', 'PurchaseOrderController@finalupdate');
        Route::post('delete', 'PurchaseOrderController@delete');

        Route::post('addVariable', 'PurchaseOrderController@addVariable');
        Route::post('deleteVariable', 'PurchaseOrderController@deleteVariable');
    });

    Route::group([
        'prefix' => 'demandform',
    ], function () {
        Route::get('', 'DemandFormController@index')->name('demandform');
        Route::get('getSupplierStore', 'DemandFormController@getSupplierStore');

        Route::post('add', 'DemandFormController@add');
        Route::post('updateQuantity', 'DemandFormController@updateQuantity');
        Route::post('updateRate', 'DemandFormController@updateRate');
        Route::post('delete', 'DemandFormController@delete');
        Route::get('getUnsavedGeneralDemands', 'DemandFormController@getUnsavedGeneralDemands');

        Route::get('getQuotationNoOrders', 'DemandFormController@getQuotationNoOrders');
        Route::post('finalsave', 'DemandFormController@finalsave');
        Route::post('verify', 'DemandFormController@verify');

        Route::get('report', 'DemandFormController@report');
        Route::get('getSupplierDemands', 'DemandFormController@getSupplierDemands');
        Route::get('getPendingSupplierDemands', 'DemandFormController@getPendingSupplierDemands');

        Route::get('getMedicineList', 'DemandFormController@getMedicineList');
    });
});

Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'inventory', 'as' => 'inventory.'], function () {

    Route::post('/nepalitoenglish', 'StoreController@nepalitoenglishdate')->name('nepalitoenglish');
    Route::post('/englishtonepali', 'StoreController@englishtonepalidate')->name('englishtonepali');

    Route::group(['prefix' => 'purchaseentry', 'as' => 'purchase-entry.'], function () {
        Route::get('/', 'PurchaseEntryController@index')->name('index');
        Route::post('/supplieraddress', 'PurchaseEntryController@supplierAddress')->name('supplieraddress');
        Route::post('/getmedicine', 'PurchaseEntryController@getMedicineFromFldroute')->name('getmedicine');
        Route::post('/showall', 'PurchaseEntryController@ShowAllPurchaselistFromComp')->name('showall');
        Route::get('/exportpdfreprint/{fldreference}', 'PurchaseEntryController@exportPdfReprint')->name('exportreprintpdf');
        Route::post('/checkexpiry', 'PurchaseEntryController@checkExpiry')->name('checkexpiry');
        Route::post('/netunitcostcalc', 'PurchaseEntryController@netUnitCostCalc')->name('netunitcostcalc');
        Route::post('/savepurchasedata', 'PurchaseEntryController@savePurchaseData')->name('savepurchasedata');

        Route::post('/savepurchasespurchasebill', 'PurchaseEntryController@savePurchasesPurchasebill')->name('savepurchasespurchasebill');
        Route::get('/exportpdfsave/{billno}', 'PurchaseEntryController@exportPdfSave')->name('exportpdfsave');
    });

    Route::group(['prefix' => 'stockreturn', 'as' => 'stock-return.'], function () {
        Route::get('/', 'StockReturnController@index')->name('index');
        Route::post('/loadparticulars', 'StockReturnController@loadParticularsFromStockReturn')->name('loadparticulars');
        Route::get('/exportparticularspdf/{fldcomp}', 'StockReturnController@exportParticularsPdf')->name('exportparticularspdf');
        Route::post('/checkstockidintblentry', 'StockReturnController@checkstockidintblentry')->name('checkstockidintblentry');
        Route::post('/insertstockreturn', 'StockReturnController@insertStockReturn')->name('insertstockreturn');
        Route::get('/exportpdfreprint', 'StockReturnController@exportPdfReprint')->name('exportpdfreprint');
        Route::get('/savestockreturn', 'StockReturnController@saveStockReturn')->name('savestockreturn');

        Route::get('/get-reference-number', 'StockReturnController@getRefNo')->name('get.reference');
        Route::get('/get-pending-stockreturns', 'StockReturnController@getPendingStockreturns')->name('get.pendingStockreturns');
        Route::get('/medicine', 'StockReturnController@getMedicine')->name('get.medicine');
        Route::get('/medicine-with-reference', 'StockReturnController@getMedicineWithReference')->name('stock.return.medicineWithReference');
        Route::get('/batch', 'StockReturnController@getBatch')->name('get.batch');
        Route::get('/expiry', 'StockReturnController@getExpiry')->name('get.expiry');
        Route::post('/save-stock-return', 'StockReturnController@saveStockReturns')->name('get.insertStockReturn');
        Route::post('/save-final', 'StockReturnController@finalSave')->name('get.final.save');
        Route::post('/delete', 'StockReturnController@deleteEntry')->name('delete');

    });

    Route::group(['prefix' => 'stock-transfer', 'as' => 'stock-transfer.'], function () {
        Route::get('/', 'StockTransferController@index')->name('index');
        Route::post('/change-route-stock-transfer', 'StockTransferController@changeRoute')->name('change.route.stock.transfer');
        Route::post('/stock-medicine-details', 'StockTransferController@changeStock')->name('stock.medicine.details');
        Route::post('/stock-batch-change', 'StockTransferController@batchChange')->name('stock.medicine.batch.change');
        Route::post('/stock-add', 'StockTransferController@addStockConsumed')->name('stock.medicine.add');
        Route::get('/export-report', 'StockTransferController@exportReport')->name('stock.export.report');

        /*stock send*/
        Route::post('/get-list-of-medicine', 'StockTransferController@getMedicineList')->name('get.list.of.medicine');
        Route::post('/get-list-of-medicine-batch', 'StockTransferController@getMedicineListBatch')->name('get.list.of.medicine.batch');
        Route::post('/transfer-medicine-add', 'StockTransferController@addTransfer')->name('medicine.add.transfer');
        Route::post('/transfer-medicine-save', 'StockTransferController@saveTransfer')->name('medicine.save.transfer');

        Route::get('getItemByDemandNumber', 'StockTransferController@getItemByDemandNumber')->name('getItemByDemandNumber');
        Route::get('getItemByBarcode', 'StockTransferController@getItemByBarcode')->name('getItemByBarcode');
        Route::post('saveItemByDemandNumber', 'StockTransferController@saveItemByDemandNumber')->name('saveItemByDemandNumber');

        Route::get('getPendingListsByDept', 'StockTransferController@getPendingListsByDept')->name('getPendingListsByDept');
        Route::get('getReceivedListsByDept', 'StockTransferController@getReceivedListsByDept')->name('getReceivedListsByDept');
        Route::post('confirmStockReceive', 'StockTransferController@confirmStockReceive')->name('confirmStockReceive');

    });

    Route::group(['prefix' => 'stock-consume', 'as' => 'stock-consume.'], function () {
        Route::get('/', 'StockConsumeController@index')->name('index');
        Route::post('/add-stock-target', 'StockConsumeController@addStockTarget')->name('add.stock.target');
        Route::post('/list-stock-target', 'StockConsumeController@listStockConsumed')->name('list.stock.target');
        Route::post('/change-route-stock', 'StockConsumeController@changeRoute')->name('change.route.stock');
        Route::post('/stock-medicine-details', 'StockConsumeController@changeStock')->name('stock.medicine.details');
        Route::post('/stock-batch-change', 'StockConsumeController@batchChange')->name('stock.medicine.batch.change');
        Route::post('/stock-add', 'StockConsumeController@addStockConsumed')->name('stock.medicine.add');
        Route::post('/stock-consume-save', 'StockConsumeController@finalSave')->name('stock.save.consume');
        Route::get('/export-report', 'StockConsumeController@exportReport')->name('stock.export.report');
    });

    Route::group(['prefix' => 'stock-consume-return', 'as' => 'stock-consume-return.'], function () {
        Route::get('/', 'StockConsumeReturnController@index')->name('index');
        Route::get('/exportpdfreprint', 'StockConsumeReturnController@exportPdfReprint')->name('exportpdfreprint');
        Route::get('/get-reference-number', 'StockConsumeReturnController@getRefNo')->name('get.reference');
        Route::get('/get-pending-stockconsumereturns', 'StockConsumeReturnController@getPendingStockconsumereturns')->name('get.pendingStockconsumereturns');
        Route::get('/medicine', 'StockConsumeReturnController@getMedicine')->name('get.medicine');
        Route::get('/batch', 'StockConsumeReturnController@getBatch')->name('get.batch');
        Route::get('/expiry', 'StockConsumeReturnController@getExpiry')->name('get.expiry');
        Route::post('/save-consume-return', 'StockConsumeReturnController@saveConsumeReturns')->name('get.insertConsumeReturn');
        Route::post('/save-final', 'StockConsumeReturnController@finalSave')->name('get.final.save');

    });

    Route::group(['prefix' => 'stock-adjustment', 'as' => 'stock-adjustment.'], function () {
        Route::get('/', 'StockAdjustmentController@index')->name('index');
        Route::post('/change-route-stock', 'StockAdjustmentController@changeRoute')->name('change.route.stock');
        Route::post('/stock-medicine-details', 'StockAdjustmentController@changeStock')->name('stock.medicine.details');
        Route::post('/stock-batch-change', 'StockAdjustmentController@batchChange')->name('stock.medicine.batch.change');
        Route::post('/stock-add', 'StockAdjustmentController@addStockAdjustment')->name('stock.medicine.add');
        Route::post('/stock-adjustment-save', 'StockAdjustmentController@finalSave')->name('stock.save.consume');
        Route::get('/export-report', 'StockAdjustmentController@exportReport')->name('stock.export.report');
        Route::post('/authenticate/user', 'StockAdjustmentController@authenticateUser')->name('authenticate.user');
    });
});
