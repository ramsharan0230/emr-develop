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
], function () {
	Route::group([
		'prefix' => 'dispensingForm',
	], function () {
		Route::get('resetEncounter', 'DispensingFormController@resetEncounter');
		Route::any('/', 'DispensingFormController@index')->name('dispensingForm')->middleware('slash');
		Route::get('getMedicineList', 'DispensingFormController@getMedicineList');
		Route::post('saveMedicine', 'DispensingFormController@saveMedicine');
		Route::post('enablePharmacy', 'DispensingFormController@enablePharmacy')->name('enablePharmacy');
		Route::post('saveMedicines', 'DispensingFormController@saveMedicines');
		Route::post('deleteMedicine', 'DispensingFormController@deleteMedicine');
		Route::post('deleteTPItem', 'DispensingFormController@deleteTPItem');

		Route::post('updateDetails', 'DispensingFormController@updateDetails');
		Route::post('updateTPItem', 'DispensingFormController@updateTPItem');
		Route::post('updateEntity', 'DispensingFormController@updateEntity');
		Route::post('updateTPItemQuantity', 'DispensingFormController@updateTPItemQuantity');
		Route::get('showInfo', 'DispensingFormController@showInfo');
		Route::get('generatePdf', 'DispensingFormController@generatePdf');
		Route::get('getPatientMedicine', 'DispensingFormController@getPatientMedicine');
		Route::post('getTPBillList', 'DispensingFormController@getTPBillList');
		Route::get('validateDispense', 'DispensingFormController@validateDispense');
		Route::get('print', 'DispensingFormController@print');
		Route::get('tpbill', 'DispensingFormController@tpbill');

		Route::get('getOnlineRequest', 'DispensingFormController@getOnlineRequest');

		//update consultant
		Route::post('update-consultant', 'DepositFormController@updateConsult')->name('dispensing.consult.update');
	});

	Route::group([
		'prefix' => 'returnForm',
	], function () {
		Route::get('/', 'ReturnFormController@index')->name('returnForm');
		Route::get('getPatientDetail', 'ReturnFormController@getPatientDetail');
		Route::get('getEntryList', 'ReturnFormController@getEntryList');
		Route::post('returnEntry', 'ReturnFormController@returnEntry');
		Route::post('returnBill', 'ReturnFormController@returnBill');
		Route::post('deleteReturnEntry', 'ReturnFormCashierController@deleteReturnEntry');

		Route::post('getReturnItems', 'ReturnFormController@getReturnItems');
		Route::post('save-and-bill', 'ReturnFormController@saveAndBill');

		Route::post('changeQuantity', 'ReturnFormController@changeQuantity');
	});

	Route::group([
		'prefix' => 'dispensingList',
	], function () {
		Route::get('/', 'DispensingListController@index')->name('dispensingList');
		Route::get('getDepartments', 'DispensingListController@getDepartments');
		Route::get('getPatients', 'DispensingListController@getPatients');
		Route::get('getPatientMedicines', 'DispensingListController@getPatientMedicines');
		Route::get('dispense', 'DispensingListController@dispense');
		Route::post('changeQuantity', 'DispensingListController@changeQuantity');
		Route::get('export-dispensed-medicines', 'DispensingListController@exportMedicines');
	});

	Route::group([
		'prefix' => 'depositForm',
	], function () {
		Route::any('/', 'DepositFormController@index')->name('depositForm');
		Route::post('/get-expenses', 'DepositFormController@expensesList')->name('depositForm.expenses.list');
		Route::post('/get-invoices', 'DepositFormController@getInvoiceList')->name('depositForm.invoice.list');
		Route::post('/save-comment', 'DepositFormController@saveComment')->name('depositForm.save.comment');
		Route::post('/save-diary-number', 'DepositFormController@saveDiaryNumber')->name('depositForm.save.diary.number');
		Route::post('/save-admit-consultant', 'DepositFormController@saveAdmittedConsultant')->name('depositForm.save.admitted.consultant');
		Route::get('/expenses-list/{encounter}', 'DepositFormController@expensesListPDF')->name('depositForm.expenses.pdf');
		Route::get('/invoice-list/{encounter}', 'DepositFormController@getInvoiceListPDF')->name('depositForm.invoice.pdf');
		Route::get('/deposit-report', 'DepositReportController@index')->name('deposit.display.report');
		Route::get('/searchDepositDetail', 'DepositReportController@searchDepositDetail')->name('searchDepositDetail');
		Route::get('/deposit-report/pdf', 'DepositReportController@exportPdf')->name('exportDepositPdf');
		Route::get('/deposit-report/excel', 'DepositReportController@exportDepositReportCsv')->name('exportDepositReportCsv');
		Route::get('/deposit-report-new/excel', 'DepositReportController@exportDepositReportCsvNew')->name('exportDepositReportCsvNew');

		Route::post('saveDeposit', 'DepositFormController@saveDeposit')->name('depositForm.saveDeposit');

		//condition if DEP
		Route::get('printBill', 'DepositFormController@printBill')->name('depositForm.printBill');
		// Route::post('printBill/view', 'DepositFormController@dischageClearanceBill')->name('depositForm.printBill.view');
		Route::post('printBill/view', 'DepositFormController@DepositePrintBill')->name('depositForm.printBill.view');

		Route::get('returnDeposit', 'DepositFormController@returnDeposit')->name('depositForm.returnDeposit');

		Route::post('patient/change-department', 'DepositFormController@changePatientDepartment')->name('depositForm.change-department');
		Route::get('reset-form', 'DepositFormController@reset')->name('depositForm.reset');
	});

	Route::group([
		'prefix' => 'returnFormCashier',
	], function () {
		Route::get('/', 'ReturnFormCashierController@index')->name('returnFormCashier');
		Route::get('getPatientDetail', 'ReturnFormCashierController@getPatientDetail');
		Route::get('getEntryList', 'ReturnFormCashierController@getEntryList');
		Route::post('returnEntry', 'ReturnFormCashierController@returnEntry');
		Route::post('returnBill', 'ReturnFormCashierController@returnBill');
		Route::post('deleteReturnEntry', 'ReturnFormCashierController@deleteReturnEntry');

		Route::post('save-and-bill', 'ReturnFormCashierController@saveAndBill');
	});

	Route::group([
		'prefix' => 'remarkreport',
	], function () {
		Route::get('/', 'DispensingFormController@remarkreport')->name('remarkreport');
		Route::get('remarkreportCsv', 'DispensingFormController@remarkreportCsv');
	});

	Route::group([
		'prefix' => 'reports',
	], function () {
		Route::get('expiry', 'DispensingReportController@expiry')->name('reports.expiry');
		Route::get('expiryPdf', 'DispensingReportController@expiryPdf')->name('reports.expiryPdf');
		Route::get('expiryExcel', 'DispensingReportController@expiryExcel')->name('reports.expiryExcel');

		Route::get('nearexpiry', 'DispensingReportController@nearexpiry')->name('reports.nearexpiry');
		Route::get('nearexpiryPdf', 'DispensingReportController@nearexpiryPdf')->name('reports.nearexpiryPdf');
		Route::get('nearexpiryExcel', 'DispensingReportController@nearexpiryExcel')->name('reports.nearexpiryExcel');
	});
});
Route::group([
	'middleware' => ['web', 'auth-checker'],
], function () {
	Route::group([
		'prefix' => 'deposit-credit',
	], function () {
		Route::any('/', 'DepositCreditCleranceController@index')->name('deposit.credit');
		Route::post('save-deposit', 'DepositCreditCleranceController@saveDeposit')->name('credit.save.deposit');
		Route::get('printBill', 'DepositCreditCleranceController@printBill')->name('depositForm.printBill');
	});
});
