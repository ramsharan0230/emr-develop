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

Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'mainmenu'], function () {
    Route::post('display-patient-profile-form-reports-mainmenu', array(
        'as' => 'patient.mainmenu.report.patient.profile',
        'uses' => 'PatientController@displayPatientProfile'
    ));

    Route::post('display-patient-profile-form-reports-mainmenu-update', array(
        'as' => 'patient.mainmenu.report.patient.profile.update',
        'uses' => 'PatientController@updatePatientProfile'
    ));

    Route::any('category-wise-lab-reports-mainmenu', array(
        'as' => 'report.lab-category-wise',
        'uses' => 'CategoryWiseLabReportController@categoryWiseLabReport'
    ));

    Route::any('category-wise-lab-reports-export', array(
        'as' => 'report.lab-category-wise.export',
        'uses' => 'CategoryWiseLabReportController@exportLabCatReportCsv'
    ));
    Route::any('item-ledger-report', array(
        'as' => 'item.ledger-report',
        'uses' => 'ItemledgerReportController@index'
    ));
    Route::any('live-medicine-stock', array(
        'as' => 'item.get-live-medicine-stock',
        'uses' => 'ItemledgerReportController@getLiveMedicineList'
    ));

    Route::any('get-event', array(
        'as' => 'item.getEvent',
        'uses' => 'ItemledgerReportController@getEvent'
    ));

    Route::any('live-medicine-stock-change', array(
        'as' => 'item.get-live-medicine-stock-change',
        'uses' => 'ItemledgerReportController@getLiveMedicineListChange'
    ));

    Route::any('item-ledger-report-pdf', array(
        'as' => 'item.ledger.report.pdf',
        'uses' => 'ItemledgerReportController@exportNewItemLedgerPdf'
    ));

    Route::any('export-item-ledger-excel', array(
        'as' => 'export.item.ledger.excel',
        'uses' => 'ItemledgerReportController@exportNewItemLedgerExcel'
    ));
    Route::any('item-ledger-report-list', array(
        'as' => 'item.ledger-report-list',
        'uses' => 'ItemledgerReportController@searchNewLedgerReport'
    ));

    Route::any('search-patient', array(
        'as' => 'search.patient.number',
        'uses' => 'PatientController@searchByPatientNo'
    ));

    // Item report routes
    Route::get('/item-ledger/getMedicineList', 'ItemledgerReportController@getMedicineList')->name('item.ledger.medicine-list');

    Route::get('/item-report', 'ItemReportController@index')->name('item.display.report');
    Route::get('/item-report/load-data', 'ItemReportController@loadData')->name('item.report.loaddata');
    Route::post('/item-report/get-refresh-data', 'ItemReportController@getRefreshData')->name('item.report.refreshdata');
    Route::get('/item-report/export-pdf', 'ItemReportController@exportPdf')->name('item.report.exportPdf');
    Route::get('/item-report/export-excel', 'ItemReportController@exportExcel')->name('item.report.exportExcel');
    Route::get('/item-report/datewise-pdf', 'ItemReportController@exportDatewisePdf')->name('item.report.exportDatewisePdf');
    Route::get('/item-report/categorywise-pdf', 'ItemReportController@exportCategorywisePdf')->name('item.report.exportCategorywisePdf');
    Route::get('/item-report/particularwise-pdf', 'ItemReportController@exportParticularwisePdf')->name('item.report.exportParticularwisePdf');
    Route::get('/item-report/item-details-pdf', 'ItemReportController@exportItemDetailsPdf')->name('item.report.exportItemDetailsPdf');
    Route::get('/item-report/item-date-pdf', 'ItemReportController@exportItemDatesPdf')->name('item.report.exportItemDatesPdf');
    Route::get('/item-report/item-visits-pdf', 'ItemReportController@exportItemVisitsPdf')->name('item.report.exportItemVisitsPdf');
    Route::get('/item-report/item-cut-off-amount-pdf', 'ItemReportController@exportItemCutOffAmountPdf')->name('item.report.exportItemCutOffAmountPdf');

    Route::get('/item-report/datewise-excel', 'ItemReportController@exportDatewiseExcel')->name('item.report.exportDatewiseExcel');
    Route::get('/item-report/categorywise-excel', 'ItemReportController@exportCategorywiseExcel')->name('item.report.exportCategorywiseExcel');
    Route::get('/item-report/particularwise-excel', 'ItemReportController@exportParticularwiseExcel')->name('item.report.exportParticularwiseExcel');
    Route::get('/item-report/item-details-excel', 'ItemReportController@exportItemDetailsExcel')->name('item.report.exportItemDetailsExcel');
    Route::get('/item-report/item-date-excel', 'ItemReportController@exportItemDatesExcel')->name('item.report.exportItemDatesExcel');
    Route::get('/item-report/item-visits-excel', 'ItemReportController@exportItemVisitsExcel')->name('item.report.exportItemVisitsExcel');

    // Medical report routes
    Route::get('/medical-report', 'MedicalReportController@index')->name('medical.display.report');
    Route::get('/medical-report/load-data', 'MedicalReportController@loadData')->name('medical.report.loaddata');
    Route::get('/medical-report/selectItem', 'MedicalReportController@selectItem')->name('medical.report.selectitem');
    Route::any('/medical-report/refresh-data', 'MedicalReportController@getRefreshData')->name('medical.report.refreshdata');
    Route::any('/medical-report/export-report', 'MedicalReportController@exportReport')->name('medical.report.exportReport');

    // Entry Waiting Report
    Route::get('/entry-waiting-report', 'EntryWaitingReportController@index')->name('entry-waiting.display.report');
    Route::any('/entry-waiting/refresh-data', 'EntryWaitingReportController@getRefreshData')->name('entry-waiting.report.refreshdata');
    Route::any('/entry-waiting/export-excel', 'EntryWaitingReportController@exportExcel')->name('entry-waiting.report.exportExcel');

    // Group Report
    Route::get('/group-report', 'GroupReportController@index')->name('group.display.report');
    Route::get('/group-report/get-groups', 'GroupReportController@getGroups')->name('group.getGroups');
    Route::get('/group-report/get-group-data', 'GroupReportController@getGroupData')->name('group.getGroupData');
    Route::get('/group-report/get-group-category-data', 'GroupReportController@getGroupCategoryData')->name('group.getGroupCategoryData');
    Route::post('/group-report/select-group-itemname', 'GroupReportController@selectGroupItemname')->name('group.selectGroupItemname');
    Route::get('/group-report/get-group-selected-items', 'GroupReportController@getGroupSelectedItems')->name('group.getGroupSelectedItems');
    Route::get('/group-report/get-report', 'GroupReportController@getGroupReport')->name('group.getGroupReport');
    Route::post('/group-report/get-refreshed-data', 'GroupReportController@getRefreshedData')->name('group.getRefreshedData');
    Route::get('/group-report/export-report', 'GroupReportController@exportReport')->name('group.exportReport');
    Route::get('/group-report/export-summary-report', 'GroupReportController@exportSummaryReport')->name('group.exportSummaryReport');
    Route::get('/group-report/export-datewise-report', 'GroupReportController@exportDatewiseReport')->name('group.exportDatewiseReport');
    Route::get('/group-report/export-categorywise-report', 'GroupReportController@exportCategorywiseReport')->name('group.exportCategorywiseReport');
    Route::get('/group-report/export-particular-report', 'GroupReportController@exportParticularReport')->name('group.exportParticularReport');
    Route::get('/group-report/export-detail-report', 'GroupReportController@exportDetailReport')->name('group.exportDetailReport');
    Route::get('/group-report/export-dates-report', 'GroupReportController@exportDatesReport')->name('group.exportDatesReport');
    Route::get('/group-report/export-patient-report', 'GroupReportController@exportPatientReport')->name('group.exportPatientReport');
    Route::get('/group-report/export-visits-report', 'GroupReportController@exportVisitsReport')->name('group.exportVisitsReport');
    Route::get('/group-report/remove-group-particular', 'GroupReportController@removeGroupParticular')->name('group.removeGroupParticular');

    Route::get('/group-report/export-categorywise-excel', 'GroupReportController@exportCategorywiseExcel')->name('group.exportCategorywiseExcel');
    Route::get('/group-report/export-particular-excel', 'GroupReportController@exportParticularExcel')->name('group.exportParticularExcel');
    Route::get('/group-report/export-detail-excel', 'GroupReportController@exportDetailExcel')->name('group.exportDetailExcel');
    Route::get('/group-report/export-dates-excel', 'GroupReportController@exportDatesExcel')->name('group.exportDatesExcel');
    Route::get('/group-report/export-patient-excel', 'GroupReportController@exportPatientExcel')->name('group.exportPatientExcel');
    Route::get('/group-report/export-visits-excel', 'GroupReportController@exportVisitsExcel')->name('group.exportVisitsExcel');
    Route::get('/group-report/export-summary-excel', 'GroupReportController@exportSummaryExcel')->name('group.exportSummaryExcel');
    Route::get('/group-report/export-datewise-excel', 'GroupReportController@exportDatewiseExcel')->name('group.exportDatewiseExcel');

});

Route::group(['middleware' => ['web', 'auth-checker']], function () {
    Route::get('/patient-credit-report', 'PatientCreditReportController@index')->name('patient.credit.report');
    Route::get('/patient-credit-pdf-report', 'PatientCreditReportController@patientCreditPdfReport')->name('patient.credit.pdf.report');
    Route::get('/patient-credit-excel-report', 'PatientCreditReportController@patientCreditExcelReport')->name('patient.credit.excel.report');
    Route::get('/patient-test-log-report', 'PatientTestLogReportController@index')->name('patient.test.log.report');
    Route::get('/patient-test-log-pdf-report', 'PatientTestLogReportController@patientTestLogPdfReport')->name('patient.test.log.pdf.report');
    Route::get('/patient-test-log-export-report', 'PatientTestLogReportController@patientTestLogExportReport')->name('patient.test.log.export.report');

    Route::get('/patient-credit-remarks/{encounter_id}', 'PatientCreditReportController@showRemarks')->name('patient.credit.remarks');
    Route::post('/patient-credit-remark/{encounter_id}', 'PatientCreditReportController@insertRemarks')->name('post.patient.credit.remarks');
});

Route::group(['middleware' => ['web', 'auth-checker']], function () {
    Route::get('/patient-ledger-report', 'PatientLedgerReportController@index')->name('patient.ledger.report');
    Route::get('/patient-ledger-getPatientData', 'PatientLedgerReportController@getPatientData')->name('patient.ledger.getPatientData');
    Route::get('/patient-ledger-getBillData', 'PatientLedgerReportController@getBillData')->name('patient.ledger.getBillData');
    Route::get('/patient-ledger-search-encounter', 'PatientLedgerReportController@searchPatientEncounter')->name('patient.ledger.search.encounter');

});

/**Inventory report*/
Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'inventory-report'], function () {
    Route::get('/', 'InventoryReportController@index')->name('inventory.display.report');
    Route::post('/list-data', 'InventoryReportController@getInventoryData')->name('inventory.list.data');
    Route::get('/generate-report', 'InventoryReportController@generateInventoryReport')->name('inventory.report.generate');
    Route::get('/generate-report-excel', 'InventoryReportController@transaction')->name('inventory.report.excel.generate');
    Route::get('/filter-data', 'InventoryReportController@filterData')->name('inventory.report.filter.data');
});

Route::group(['middleware' => ['web', 'auth-checker']], function () {
    Route::match(['get', 'post'], 'ipevent', 'IpEventController@index')->name('ipevent.index');
    Route::match(['get', 'post'], 'visit', 'VisitController@index')->name('visit.index');
});

Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'demand-report'], function () {

    Route::get('/', 'DemandformReportController@index')->name('demand.report');
    Route::get('/getMedicineList', 'DemandformReportController@getMedicineList')->name('demand.report.getMedicineList');
    Route::get('/report', 'DemandformReportController@report')->name('demand.report.getReport');
    Route::get('/report/excel', 'DemandformReportController@export')->name('demand.report.export');
    Route::get('/getBillNo', 'DemandformReportController@getBillNo')->name('demand.report.getBill');
});

Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'purchase-report'], function () {

    Route::get('/', 'PurchaseReportController@index')->name('purchase.report');
    Route::get('/getMedicineList', 'PurchaseReportController@getMedicineList')->name('purchase.report.getMedicineList');
    Route::get('/report', 'PurchaseReportController@report')->name('purchase.report.getReport');
    Route::get('/report/excel', 'PurchaseReportController@export')->name('purchase.report.excel');
    Route::get('/getBillNo', 'PurchaseReportController@getBillNo')->name('purchase.report.getBill');
});

Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'purchase-entry-report'], function () {

    Route::get('/', 'PurchaseEntryReportController@index')->name('purchase.entry.report');
    Route::get('/getList', 'PurchaseEntryReportController@getList')->name('purchase.entry.report.getList');
    Route::get('/report', 'PurchaseEntryReportController@report')->name('purchase.entry.report.getReport');
    Route::get('/report/excel', 'PurchaseEntryReportController@exportExcel')->name('purchase.entry.report.excel');
    Route::get('/getBillNo', 'PurchaseEntryReportController@getBillNo')->name('purchase.entry.report.getBill');
    Route::get('/getpurchasesupplyreport', 'PurchaseEntryReportController@PurchaseEntrySuppliersWise')->name('purchase.entry.report,supply');
});

Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'stock-return'], function () {

    Route::get('/', 'StockReturnController@index')->name('stock.return.report');
    Route::get('/getList', 'StockReturnController@getList')->name('stock.return.report.getList');
    Route::get('/report', 'StockReturnController@report')->name('stock.return.report.getReport');
    Route::get('/report/excel', 'StockReturnController@exportExcel')->name('stock.return.report.excel');
    Route::get('/getReference', 'StockReturnController@getReference')->name('stock.return.getReference');
});

Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'stock-consume'], function () {

    Route::get('/', 'StockConsumeController@index')->name('stock.consume.report');
    Route::get('/getList', 'StockConsumeController@getList')->name('stock.consume.report.getList');
    Route::get('/report', 'StockConsumeController@report')->name('stock.consume.report.getReport');
    Route::get('/report/excel', 'StockConsumeController@exportExcel')->name('stock.consume.report.excel');
    Route::get('/getReference', 'StockConsumeController@getReference')->name('stock.consume.getReference');
});


Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'stock-transfer'], function () {

    Route::get('/', 'StockTransferController@index')->name('stock.transfer.report');
    Route::get('/getList', 'StockTransferController@getList')->name('stock.transfer.report.getList');
    Route::get('/report', 'StockTransferController@report')->name('stock.transfer.report.getReport');
    Route::get('/report/excel', 'StockTransferController@exportExcel')->name('stock.transfer.report.excel');
});

Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'under-stock'], function () {

    Route::get('/', 'UnderStockController@index')->name('under.stock.report');
    Route::get('/getList', 'UnderStockController@getList')->name('under.stock.getList');
    Route::get('/report', 'UnderStockController@report')->name('under.stock.getReport');
    Route::get('/report/excel', 'UnderStockController@exportExcel')->name('under.stock.excel');
});


Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'order-vs-receive'], function () {

    Route::get('/', 'OrderVsReciveController@index')->name('order.vs.receive.report');
    Route::get('/getList', 'OrderVsReciveController@getList')->name('order.vs.receive.getList');
    Route::get('/report', 'OrderVsReciveController@report')->name('order.vs.receive.getReport');
    Route::get('/report/excel', 'OrderVsReciveController@exportExcel')->name('order.vs.receive.excel');
    Route::get('/getReferences', 'OrderVsReciveController@getReferences')->name('order.vs.receive.getReferences');
});

Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'demand-vs-order-vs-receive'], function () {

    Route::get('/', 'DemandVsOrderVsPurchaseController@index')->name('demand-vs-order-vs-receive.report');
    Route::get('/getList', 'DemandVsOrderVsPurchaseController@getList')->name('demand-vs-order-vs-receive.getList');
    Route::get('/report', 'DemandVsOrderVsPurchaseController@report')->name('demand-vs-order-vs-receive.getReport');
    Route::get('/report/excel', 'DemandVsOrderVsPurchaseController@exportExcel')->name('demand-vs-order-vs-receive.excel');
    Route::get('/getReferences', 'DemandVsOrderVsPurchaseController@getReferences')->name('order.vs.receive.getReferences');
});
Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'reorder-level'], function () {
    Route::get('/', 'ReorderLevelReportController@index')->name('reorder-level.display.report');
    Route::get('/load-data', 'ReorderLevelReportController@loadData')->name('reorder-level.report.loaddata');
    Route::post('/get-refresh-data', 'ReorderLevelReportController@getRefreshData')->name('reorder-level.report.refreshdata');

});

Route::get('/pathology-report', 'PathologyReportController@index')->name('pathology.count');
Route::post('/pathology-generate-report', 'PathologyReportController@generatereport')->name('pathology.count.generate.report');


// Discharge Bills Report
Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'discharge-bills'], function () {
    Route::get('/', 'DischargeBillsReportController@index')->name('discharge-bills.index');
    Route::any('/get-refresh-data', 'DischargeBillsReportController@getRefreshData')->name('discharge-bills.refreshdata');
    Route::any('/get-export-data', 'DischargeBillsReportController@getExportData')->name('discharge-bills.exportdata');
});
// Service Cost Report
Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'report/service-cost'], function () {
    Route::get('/', 'ServiceCostReportController@index')->name('service.cost.report.index');
    Route::get('/search', 'ServiceCostReportController@search')->name('service.cost.report.search');
    Route::get('/export', 'ServiceCostReportController@excelExport')->name('service.cost.report.export');
});

Route::group([
    'middleware' => ['web', 'auth-checker'],
    'prefix' => 'purchase-entry-edit',
], function() {
    Route::get('/', 'PurchaseEntryEditController@index')->name('purchase.edit');
    Route::get('/reference', 'PurchaseEntryEditController@getRefrence')->name('purchase.edit.reference');Route::get('/reference', 'PurchaseEntryEditController@getRefrence')->name('purchase.edit.reference');
    Route::get('/get-purchase-entries', 'PurchaseEntryEditController@getPurchaseEntries')->name('purchase.edit.getPurchaseEntries');
    Route::get('/edit', 'PurchaseEntryEditController@editPurchaseEntry')->name('purchase.edit.editPurchaseEntry');
    Route::post('/update', 'PurchaseEntryEditController@updatePurchaseEntry')->name('purchase.edit.updatePurchaseEntry');
    Route::get('/delete', 'PurchaseEntryEditController@deletePurchaseEntry')->name('purchase.edit.deletePurchaseEntry');
    Route::post('/destory', 'PurchaseEntryEditController@destoryPurchaseEntry')->name('purchase.edit.destoryPurchaseEntry');
});




Route::group([
    'middleware' => ['web', 'auth-checker'],
    'prefix' => 'pharmacy-sales',
], function() {
    Route::get('/', 'PharmacyReportController@index')->name('pharmacy.sales');
    Route::get('/pharmacy-sales-report', 'PharmacyReportController@getList')->name('pharmacy.sales.report');
    Route::get('/narcotic-sales-report', 'NarcoticDispenseController@index')->name('narcotic-sales-report');
    Route::get('/narcotic-sales-report-pdf', 'NarcoticDispenseController@export')->name('narcotic-sales-report-pdf');
    Route::get('/narcotic-sales-report-excel', 'NarcoticDispenseController@exportExcel')->name('narcotic-sales-report-excel');
    Route::post('ajax/narcotic-sales-report-pdf', 'NarcoticDispenseController@export')->name('ajax.narcotic-sales-report-pdf');
    // ajax/pharmacy-sales/narcotic-sales-report-pdf
});

Route::group([
    'middleware' => ['web', 'auth-checker'],
    'prefix' => 'reconciliation',
], function() {
    Route::get('/', 'ReconciliationReportController@index')->name('reconciliation');
    Route::post('/reconciliation-report', 'ReconciliationReportController@searchReconciliation')->name('search.reconciliation');
    Route::get('/reconciliation-report-excel', 'ReconciliationReportController@exportExcel')->name('exportToExcel.reconciliation');
    Route::get('/reconciliation-summary-report-excel', 'ReconciliationReportController@exportSummaryExcel')->name('summary.exportToExcel.reconciliation');

});

