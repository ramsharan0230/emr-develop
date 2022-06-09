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

Route::prefix('coreaccount')->group(function () {
    Route::get('/', 'CoreaccountController@index');
});
/**account group*/
Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'coreaccount'], function () {
    Route::match(['get', 'post'], '/subgroup', 'CoreaccountController@subgroup')->name('subgroup');
    Route::post('addGroup', 'CoreaccountController@addGroup');
    Route::get('exportGroup', 'CoreaccountController@exportGroup');
    Route::post('get-group-list', 'CoreaccountController@listSubGroup')->name('get.group.list');
});

Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'account/transaction'], function () {
    Route::get('/', 'TransactionController@index')->name('transaction');
    Route::post('/store-transaction', 'TransactionController@store')->name('transaction.store');
    /*Route::get('/edit/{voucherNumber}', 'TransactionUpdateController@edit')->name('transaction.edit');
    Route::post('/update', 'TransactionUpdateController@update')->name('transaction.update');*/
});

Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'account/transactionsync'], function () {
    Route::get('/', 'TransactionsyncController@index')->name('transactionsync');
    Route::post('/store-transactionsync', 'TransactionsyncController@store')->name('transactionsync.store');

});

Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'account/ledger'], function () {
    Route::any('/', 'AccountLedgerController@index')->name('accounts.ledger.index');
    Route::any('/create', 'AccountLedgerController@create')->name('accounts.ledger.create');
    Route::any('/edit', 'AccountLedgerController@edit')->name('accounts.ledger.edit');
    Route::any('/change-status', 'AccountLedgerController@changeStatus')->name('accounts.ledger.changeStatus');
    Route::post('/getAccountNumber', 'AccountLedgerController@getAccountNumber')->name('accounts.ledger.getAccountNumber');
    Route::any('/delete', 'AccountLedgerController@destroy')->name('accounts.ledger.delete');
    Route::any('/ledger-lists', 'AccountLedgerController@ledgerLists')->name('accounts.ledger.lists');
});
Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'account/statement'], function () {
    Route::get('/', 'AccountStatementController@index')->name('accounts.statement.index');
    Route::get('/filter', 'AccountStatementController@filterStatement')->name('accounts.statement.filter');
    Route::get('/export', 'AccountStatementController@exportStatement')->name('accounts.statement.export');
    Route::get('/print', 'AccountStatementController@printStatement')->name('accounts.statement.print');
    Route::get('/voucher-details', 'AccountStatementController@voucherDetails')->name('accounts.voucher.details');
    Route::get('/export-voucher-details', 'AccountStatementController@exportVoucherDetails')->name('accounts.voucher.details-export');
    Route::get('/print-voucher-details', 'AccountStatementController@printVoucherDetails')->name('accounts.voucher.print-details');
});


#Trial Balance Route
Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'account/trialbalance'], function () {
    Route::get('/', 'TrialBalanceController@index')->name('accounts.trialbalance.index');

    Route::post('/searchTrialBalance', 'TrialBalanceController@searchTrialBalance')->name('searchTrialBalance');
    Route::get('/exportTrialBalance', 'TrialBalanceController@exportTrialBalance')->name('exportTrialBalance');
    Route::get('/exportTrialBalanceExcel', 'TrialBalanceController@exportTrialBalanceExcel')->name('exportTrialBalance.excel');

});
#End Trial Balance Route

#Profit Loss Route
Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'account/profitloss'], function () {
    Route::get('/', 'ProfitLossController@index')->name('accounts.profitloss.index');
    Route::post('/searchProfitLoss', 'ProfitLossController@searchProfitLoss')->name('searchProfitLoss');
    Route::get('/export-excel', 'ProfitLossController@exportExcel')->name('profitloss.export.excel');
    Route::get('/export-pdf', 'ProfitLossController@exportPdf')->name('profitloss.export.pdf');


});
#End Profit Loss Route

#Balance Sheet Route
Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'account/balancesheet'], function () {
    Route::get('/', 'BalanceSheetController@index')->name('accounts.balancesheet.index');
    Route::post('/searchBalanceSheet', 'BalanceSheetController@searchBalanceSheet')->name('searchBalanceSheet');
    Route::get('/export-balancesheet', 'BalanceSheetController@exportExcel')->name('balancesheet.excel');
    Route::get('/export-balancesheet-pdf', 'BalanceSheetController@exportPdf')->name('balancesheet.pdf');


});
#End Balance Sheet Route

Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'account/daybook'], function () {
    Route::get('/', 'AccountDaybookController@index')->name('accounts.daybook.index');
    Route::get('/filter', 'AccountDaybookController@filterDaybook')->name('accounts.daybook.filter');
    Route::get('/voucher-details', 'AccountDaybookController@voucherDetails')->name('accounts.daybook.details');
    Route::post('/getVoucherNumber', 'AccountDaybookController@getVoucherNumber')->name('accounts.daybook.getVoucherNumber');
    Route::get('/print-voucher-details', 'AccountDaybookController@printVoucherDetails')->name('accounts.daybook.print-details');
    Route::get('/close-day', 'AccountDaybookController@closeDay')->name('accounts.close.day');
    Route::get('/export-excel', 'AccountDaybookController@exportExcel')->name('accounts.day.export.excel');
    Route::get('/export-pdf', 'AccountDaybookController@exportPdf')->name('accounts.day.export.pdf');
});

/**account group map*/
Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'account-map'], function () {
    Route::get('/', 'GroupMapController@subgroup')->name('get.group.map.index');
    Route::post('get-group-list', 'GroupMapController@listSubGroup')->name('get.group.map.list');
    Route::post('get-map-item-type', 'GroupMapController@getItemType')->name('get.group.map.item.type');
    Route::post('get-map-item-service', 'GroupMapController@getItemService')->name('get.group.map.item.service');
    Route::post('get-map-item-add', 'GroupMapController@addMapData')->name('get.group.map.item.add');
});
Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'account-sync'], function () {
    Route::get('/map-list-by-account', 'AccountSyncController@syncListByAccount')->name('map.list.by.account');
    Route::post('/map-sync-by-account/{AccountNo}', 'AccountSyncController@syncAccountByNo')->name('map.sync.by.account');
    Route::get('/transaction-sync', 'TransactionController@tempTransactionSync')->name('transaction.sync.by.account');

    Route::get('/transaction-view/{AccountNo}', 'TransactionController@viewMapItems')->name('transaction.view.by.account');
    Route::get('/transaction-miscellaneous', 'TransactionController@miscellaneous')->name('transaction.view.miscellaneous');
/*account-sync/map-list-by-account*/
    Route::post('/map-sync-all', 'AccountSyncController@tempTransactionSyncAll')->name('map.sync.all');
    Route::any('/map-add-all', 'TransactionController@tempTransactionAddAll')->name('transaction.add.all');
    Route::post('/transaction-add', 'TransactionController@tempTransactionAdd')->name('transaction.add.by.account');
/*account-sync/map-list-by-account*/
});
/**doctor share*/
Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => ''], function () {
    Route::get('/transaction-doctor-list', 'DoctorExpensesController@index')->name('transaction.view.doctor');
    Route::get('/ledger-doctor-map', 'DoctorExpensesController@mapDoctorLedger')->name('transaction.map.doctor');
    Route::get('/ledger-doctor-create', 'DoctorExpensesController@create')->name('transaction.create.doctor.map');
    Route::post('/ledger-doctor-create-temp/{doctor_id}', 'DoctorExpensesController@insertTemp')->name('transaction.create.doctor.temp');

    Route::get('/ledger-doctor-sync-transaction/{user_id}/{from_date}/{to_date}', 'DoctorExpensesController@syncTransaction')->name('transaction.create.doctor.sync.transaction');
    Route::get('/ledger-doctor-sync-transaction-all', 'DoctorExpensesController@syncTransactionAll')->name('transaction.doctor.sync.transaction.all');
    Route::post('/ledger-doctor-create-transaction-master', 'DoctorExpensesController@insertTransactionMaster')->name('transaction.create.doctor.transaction.master');
    Route::post('/sync-all-data', 'DoctorExpensesController@syncAllDoctorsData')->name('transaction.sync.all.doctor');
});

/**Discount map and sync*/
Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => ''], function () {
    Route::get('/discount-map', 'DiscountController@index')->name('discount.map');
    Route::post('/discount-map-create', 'DiscountController@createMap')->name('transaction.create.discount.ledger');
    Route::post('/discount-map-general-create', 'DiscountController@createMapGeneral')->name('transaction.create.general.ledger');
});

#Credit Clearance Route
Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'account/creditclearance'], function () {
    Route::get('/', 'CreditClearanceController@index')->name('accounts.creditclearance.index');

    Route::post('/searchBill', 'CreditClearanceController@searchBill')->name('searchBill');
    Route::post('/listBillingItems', 'CreditClearanceController@listBillingItems')->name('billing.item.list');
    Route::post('/save-credit-bill', 'CreditClearanceController@saveCreditClerance')->name('billing.item.save');
});
#End Credit Clearance Route

/**Account head wise ledger*/
Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'account/head-wise-ledger'], function () {
    Route::get('/', 'HeadWiseLedgerController@index')->name('accounts.head.wise.ledger.index');
    Route::get('/export', 'HeadWiseLedgerController@export')->name('accounts.head.wise.ledger.export');
    Route::get('/exportToExcel', 'HeadWiseLedgerController@exportToExcel')->name('accounts.head.wise.ledger.export.excel');
});

/*Transaction update*/
Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'yRNVGXQbEv'], function () {
    Route::get('/{initials}', 'UpdateVoucherNoController@updateVoucherNumber');
});

Route::get('/change_depart_append', 'TransactionController@change_depart_append');





