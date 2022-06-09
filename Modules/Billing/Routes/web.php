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

Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'billing/service'], function () {
    Route::any('/', 'BillingController@index')->name('billing.display.form')->middleware('slash');
    Route::any('/pharmacy', 'BillingController@pharmacyBilling')->name('billing.display.form.pharmacy');
    Route::any('/get-items-by-service-or-inventory', 'BillingController@getItemsByServiceOrInventory')->name('billing.get.items.by.service.or.inventory');
    Route::any('/billing-userpay-save', 'BillingController@saveUserPay')->name('billing.userpay.save');
    Route::any('/get-items-by-service-or-inventory-select', 'BillingController@getItemsByServiceOrInventorySelect')->name('billing.get.items.by.service.or.inventory.select');
    Route::post('/billing-get-opd-data', 'BillingController@getOpdData')->name('billing.get.opd.data');
    Route::post('/save-service', 'BillingController@saveServiceCosting')->name('billing.save.items.by.service');
    Route::post('/delete-service', 'BillingController@deleteServiceCosting')->name('billing.delete.items.by.service');
    Route::post('/get-data-for-temporary', 'BillingController@getDataTemporaryBilling')->name('billing.display.temporary.data');
    Route::post('/change-rate', 'BillingFunctionsController@changeRate')->name('billing.change.rate.service');
    Route::post('/change-quantity', 'BillingFunctionsController@changeQuantity')->name('billing.change.quantity.service');
    Route::post('/change-discount', 'BillingFunctionsController@changeDiscount')->name('billing.change.discount.service');
    Route::post('/change-discount-mode', 'BillingFunctionsController@changeDiscountMode')->name('billing.change.discountMode.service');
    Route::post('/change-discount-bulk', 'BillingFunctionsController@changeDiscountBulk')->name('billing.change.discountBulk.service');
    Route::post('/change-discount-percent-bulk', 'BillingFunctionsController@changeDiscountPercentBulk')->name('billing.change.discount.percent.bulk.service');

    Route::post('/billing-package-data', 'BillingFunctionsController@getPackageItemList')->name('billing.package.data');
    
    Route::post('/final-save-payment', 'BillingController@finalPayment')->name('billing.final.save.payment')->middleware('date-slash');
    Route::post('/tp-bill', 'TpBillingController@tpBill')->name('billing.tp.bill');
    Route::get('/tp-display-invoice', 'TpBillingController@displayInvoice')->name('tp.billing.display.invoice');
    Route::get('/display-invoice', 'BillingFunctionsController@displayInvoice')->name('billing.display.invoice');
    Route::get('/billing-display-invoice-service', 'BillingFunctionsController@displayInvoiceService')->name('billing.display.invoice.service');
    Route::get('/billing-display-invoice', 'BillingFunctionsController@displayInvoiceBilling')->name('cashier.form.display.invoice');
    Route::get('/billing-report', 'BillingReportController@index')->name('billing.display.report');
    Route::get('/billing-report-new', 'BillingReportController@indexNew')->name('billing.display.report.new');
    Route::post('/get-package', 'BillingReportController@getPackage')->name('billing.package');

   //start first cond
    Route::get('/billing-invoice', 'BillingReportController@generateInvoice')->name('billing.user.report');
    Route::post('/billing-invoice/bill', 'BillingReportController@generateInvoiceBill')->name('billing.user.report.view');
    Route::post('/billing-invoice/bill/print', 'BillingReportController@billingInvoicePrint')->name('billing.user.report.print');


    //first condition
    Route::get('/billing-user-report', 'BillingReportController@generateUserReport')->name('billing.invoice');
    Route::any('/searchBillingDetail', 'BillingReportController@searchBillingDetail')->name('searchBillingDetail');
    Route::any('new/searchBillingDetail', 'BillingReportController@newSearchBillingDetail')->name('newSearchBillingDetail');
    Route::post('/billing-user-list', 'BillingReportController@listUser')->name('billing.user.list');
    Route::get('/billing-invoice-list', 'BillingReportController@invoicePdf')->name('billing.invoice.list');
    Route::get('/billing-group-report', 'BillingReportController@groupPdf')->name('billing.group.report');
    Route::post('/getQuantityChartDetail', 'BillingReportController@getQuantityChartDetail')->name('getQuantityChartDetail');
    Route::any('new/searchBillingDetailColumn', 'BillingReportController@searchBillFromKeyword')->name('newSearchBillingDetail.keyword');

    //having start with re RET
    Route::get('displayReturnBilling', 'BillingFunctionsController@displayReturnBilling')->name('billing.displayReturnBilling');
    Route::post('displayReturnBilling/billview', 'BillingFunctionsController@displayReturnBillingView')->name('billing.displayReturnBilling.view');
    Route::post('displayReturnBilling/bill/print', 'BillingFunctionsController@displayReturnBillingPrint')->name('billing.displayReturnBilling.print');

    /** Health Serive TAX Report */
    Route::get('/service-tax-report-pdf', 'BillingReportController@taxreportpdf')->name('service-tax-report-pdf');
    Route::get('/service-tax-export-pdf', 'BillingReportController@taxexportpdf')->name('service-tax-export-pdf');

    /**change billing mode*/
    Route::post('/billing-display-change-discount-mode', 'BillingFunctionsController@changeDiscountMode')->name('billing.display.change.discount.mode');

    Route::get('/export-billing-report', array(
        'as' => 'export.billing.report',
        'uses' => 'BillingReportController@exportBillingReport'
    ));
    Route::get('/export-billing-report-excel', array(
        'as' => 'export.billing.report.excel',
        'uses' => 'BillingReportController@exportBillingReportExcel'
    ));
    Route::get('/export-billing-detail-report-excel', array(
        'as' => 'export.billing.detail.report.excel',
        'uses' => 'BillingReportController@exportBillingReportDetailExcel'
    ));
    Route::post('/asyn/export-billing-detail-report-excel', array(
        'as' => 'asyn.export.billing.detail.report.excel',
        'uses' => 'BillingReportController@exportBillingReportDetailExcel'
    ));
    Route::get('/asyn/export-billing-detail-report-excel-download',[
        'as' => 'asyn.export.billing.detail.report.excel.download',
        'uses' => 'BillingReportController@exportBillingReportDetailExcelDownload'
    ]);

    Route::get('/get-doctors-list/{billingId}/{category}', array(
        'as' => 'billing.doctor-list',
        'uses' => 'BillingFunctionsController@getDoctorList'
    ));

    Route::get('/get-doctor-share', array(
        'as' => 'billing.get-doctor-share',
        'uses' => 'BillingFunctionsController@getDoctorShare'
    ));

    Route::get('/get-ot-doctor-share', array(
        'as' => 'billing.get-ot-doctor-share',
        'uses' => 'BillingFunctionsController@getOTDoctorShare'
    ));

    Route::post('billing-show-add-group', array(
        'as' => 'billing.show.add.group',
        'uses' => 'BillingController@addGroupTest'
    ));

    Route::post('billing-doctor-share', array(
        'as' => 'billing.doctor-share',
        'uses' => 'BillingController@saveDoctorShare'
    ));

    Route::post('updateDoctorShareReferral', array(
        'as' => 'billing.updateDoctorShareReferral',
        'uses' => 'BillingController@updateDoctorShareReferral'
    ));

    Route::get('/collection-report', 'CollectionReportController@index')->name('collection.display.report');
    Route::get('/collection-invoice', 'CollectionReportController@collection-invoice')->name('collection.invoice');
    Route::any('/searchCollectionBillingDetail', 'CollectionReportController@searchCollectionBillingDetail')->name('searchCollectionBillingDetail');
    Route::post('/getQuantityChartDetail', 'CollectionReportController@getQuantityChartDetail')->name('getQuantityChartDetail');
    Route::get('/export-collection-report', array(
        'as' => 'export.collection.report',
        'uses' => 'CollectionReportController@exportUserCollectionReport'
    ));
    Route::get('/export-collection-excel-report', array(
        'as' => 'export.collection.excel.report',
        'uses' => 'CollectionReportController@exportUserCollectionExcelReport'
    ));

    Route::get('/user-wise-card-cash-report', 'UserWiseCardCashReportController@index')->name('user.wise.card.cash.report');
    Route::any('/search-user-wise-card-cash', 'UserWiseCardCashReportController@searchUserWiseCardDetail')->name('search.user.wise.card.cash');
    Route::get('/export-user-wise-card-cash-report', array(
        'as' => 'export.user.wise.card.cash.report',
        'uses' => 'UserWiseCardCashReportController@exportUserWiseCardCollectionReport'
    ));

    Route::post('collection-show-add-group', array(
        'as' => 'collection.show.add.group',
        'uses' => 'CollectionController@addGroupTest'
    ));

    /**previous transaction tp and cash*/
    Route::get('previous-received-amount/{patientId}', array(
        'as' => 'previous.received.amount',
        'uses' => 'PreviousTransactionController@receivedAmount'
    ));
    Route::get('previous-tp-amount/{patientId}', array(
        'as' => 'previous.tp.amount',
        'uses' => 'PreviousTransactionController@tpAmount'
    ));

    #Deaprtment Report#
    Route::get('/department-report', 'DepartmentReportController@index')->name('department.display.report');
    Route::any('/searchDepartmentCollectionBillingDetail', 'DepartmentReportController@searchDepartmentCollectionBillingDetail')->name('searchDepartmentCollectionBillingDetail');
    Route::any('/searchCategoryWiseReport', 'DepartmentReportController@searchCategoryWiseReport')->name('department.searchCategoryWiseReport');
    Route::get('/export-department-report', array(
        'as' => 'export.department.report',
        'uses' => 'DepartmentReportController@exportDepartmentCollectionReport'
    ));

    Route::get('/export-department-wise-revenue', array(
        'as' => 'export.department.revenue.report',
        'uses' => 'DepartmentReportController@exportDepartmentWiseRevenueReport'
    ));

    Route::get('/export-department-wise-report', array(
        'as' => 'export.department-wise.report',
        'uses' => 'DepartmentReportController@exportDepartmentWiseReport'
    ));

    Route::get('/export-new-report', array(
        'as' => 'export.new.report',
        'uses' => 'DepartmentReportController@exportNewReport'
    ));
    #End Department Reprot#

    #Deposit Report#
    Route::get('/deposit-report', 'DepositReportController@index')->name('deposit.report');

    Route::any('/searchDepositDetail', 'DepositReportController@searchDepositDetail')->name('searchDepositDetail');

    #End Deposit Report#

    Route::post('save-new-patient-cashier-form', array(
        'as' => 'save.new.patient.cashier.form',
        'uses' => 'BillingFunctionsController@createUserCashBilling'
    ));

    Route::post('save-pan-number-cashier-form', array(
        'as' => 'save.pan.number.cashier.form',
        'uses' => 'BillingController@updatePanNumber'
    ));

    /**Discharge Clerance*/
    Route::any('/discharge-clearance', 'DischargeCleranceController@dischargeClearance')->name('billing.dischargeClearance');

    Route::post('/discharge-clearance-submit', 'DischargeCleranceController@finalPaymentDischarge')->name('billing.finalPaymentDischarge');


    Route::post('/discharge-clearance-refund-submit', 'DischargeCleranceController@finalPaymentDischargeRefundDeposit')->name('billing.finalPaymentDischargeRefundDeposit');
    Route::post('/discharge-clearance-refund-submit-pharmacy', 'DischargeClerancePharmacyController@finalPaymentDischargeRefundDeposit')->name('billing.finalPaymentDischargeRefundDepositPharmacy');
    Route::get('/fiscal-year-data', 'FiscalDataController@index')->name('fiscal.year.list');

    Route::post('/dischargeCsv', 'DischargesController@dischargeCsv')->name('discharge.dischargeCsv');
    Route::get('/dischargePdf', 'DischargesController@dischargePdf')->name('discharge.dischargePdf');
    Route::get('/discharge-list', 'DischargesController@index')->name('discharge.list');
    Route::any('/account-list', 'DischargesController@accountlist')->name('account.list');

    Route::get('/reset-billing-encounter', 'BillingController@resetEncounter')->name('reset.encounter.billing');
    Route::get('/print-discharge-clearance', 'DischargeCleranceController@print')->name('discharge.clearance.print');
    Route::post('/discharge-clearance/bill', 'DischargeCleranceController@dischageClearanceBill')->name('discharge.clearance.bill');


    Route::get('/print-discharge-clearancepharmacy', 'DischargeClerancePharmacyController@print')->name('discharge.clearance.print.pharmacy');
    Route::get('salesReport', 'BillingReportController@salesReport')->name('salesReport');
    Route::get('salesReportExport', 'BillingReportController@salesReportExport')->name('salesReportExport');
    /**pharmacy*/
    Route::any('/discharge-clearance-pharmacy', 'DischargeClerancePharmacyController@dischargeClearance')->name('billing.dischargeClearance.pharmacy');
    Route::post('/discharge-clearance-submit-pharmacy', 'DischargeClerancePharmacyController@finalPaymentDischarge')->name('billing.finalPaymentDischarge.pharmacy');
    Route::get('/discharge/printPharmacy', 'DischargeClerancePharmacyController@printPharmacy')->name('printPharmacy');


});

Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'billing/bill-status'], function () {
    Route::any('/', 'BillStatusController@index')->name('bill.status.report');
    Route::get('/searchBillStatus', 'BillStatusController@searchBillStatus')->name('searchBillStatus');
    Route::get('/pdf', 'BillStatusController@exportPdf')->name('exportBillStatusPdf');
    Route::get('/excel', 'BillStatusController@exportDepositReportCsv')->name('exportBillStatusCsv');
    Route::post('/save-referral', 'BillStatusController@saveReferral')->name('bill.status.saveReferral');
    Route::post('/save-payable', 'BillStatusController@savePayable')->name('bill.status.savePayable');
    Route::post('/cancel-patbill', 'BillStatusController@cancelPatbill')->name('bill.status.cancelPatbill');
    Route::get('/searchCancelledBill', 'BillStatusController@searchCancelledBill')->name('searchCancelledBillStatus');
});

Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'sales'], function () {
    Route::any('/', 'SalesReportController@index')->name('sales.report');
    Route::any('/searchSalesDetail', 'SalesReportController@searchSalesDetail')->name('searchSalesDetail');
    Route::get('/export-sales-report', array(
        'as' => 'export.sales.report',
        'uses' => 'SalesReportController@exportSalesData'
    ));
    Route::get('/export-sales-report-excel', array(
        'as' => 'export.sales.report.excel',
        'uses' => 'SalesReportController@exportSalesDataToExcel'
    ));
});

Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'billing/sync-ird'], function () {
    Route::get('/', 'FiscalDataController@syncFiscalWithIRD')->name('searchCancelledBill');
});

Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'tp-bill'], function () {
    Route::get('/', 'TpBillingController@listBill')->name('tp.bill.list');
    Route::post('/list-items', 'TpBillingController@listItems')->name('tp.bill.list.items');
    Route::get('/print-tp-bill', 'TpBillingController@printInvoice')->name('tp.bill.invoice.items');
    Route::get('/export-tp-bill', 'TpBillReportController@exportInvoice')->name('tp.bill.invoice.export');
});

Route::group(['middleware' => ['web', 'auth-checker']], function () {
    Route::get('/display-invoice-preview', 'DischargeCleranceController@displayInvoicePreview')->name('discharge.preview.invoice');
    Route::get('/display-pharmacy-invoice-preview', 'DischargeClerancePharmacyController@displayInvoicePreview')->name('discharge-pharmacy.preview.invoice');

    Route::get('/updatebillshare', 'BillingController@updatepatbillshare');
    Route::get('/updatebillshareopdconsult', 'BillingController@updatepatbillshareopdconsult');
});

Route::group(['middleware' => ['web', 'auth-checker']], function () {
    Route::get('/cashier-remarks', 'RemarksReportController@index')->name('remark.report.cashier');
    Route::get('/cashier-remarks-pdf', 'RemarksReportController@reportPdf')->name('remark.report.cashier.report.pdf');

    Route::get('/billing-crone/{token}', 'BillingCroneController@billingFixes');
    Route::get('/billing-crone/cash/{token}', 'JobsController@updateformaterialised');
    Route::get('/billing-crone/phm/{token}', 'JobsController@updateformaterialisedpharmacy');

    
});

    /**
     * OFF Time Report
     */
    Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'offtime'], function () {
        Route::get('/report', 'OffTimeReportController@index');
        Route::get('/filter/report', 'OffTimeReportController@filterOfftimeReport')->name('filter.offline/report');
        Route::get('/offlinereport/export-offlinereport-billing-report', 'OffTimeReportController@filterOfftimeReportPDF')->name('pdf.offline.report');
        Route::get('/offlinereport/export-billing-offline-report-excel', 'OffTimeReportController@filterOfftimeReportExcel')->name('excel.offline.report');
         
    });

