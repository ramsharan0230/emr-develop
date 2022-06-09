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
    'prefix' => 'setting'
], function () {
    Route::get('/', 'SettingController@index');
    Route::get('/report-setting', 'SettingController@reportSetting')->name('report-setting');
    Route::get('/device-setting', 'SettingController@deviceSetting')->name('device-setting');
    // Route::get('/form-setting', 'SettingController@formSetting')->name('form-setting');
    Route::get('/lab-setting', 'SettingController@labSetting')->name('lab-setting');
    Route::get('/patient-credit-color', 'SettingController@patientCreditColor')->name('patient.credit.color');
    Route::post('/patient-credit-color', 'SettingController@PatientCreditColorUpdate')->name('patient.credit.color.update');
    Route::get('/patient-setting', 'SettingController@patientReportSetting')->name('patient-setting');
    Route::post('/lab-setting/save', 'SettingController@settingSave')->name('setting.lab.save');
    Route::post('/printing-setting', 'SettingController@labPrintingTypeSetting')->name('setting.lab.printing.save');
    Route::get('/system-setting', 'SettingController@systemSetting')->name('setting.system');
    Route::post('/system-setting/store', 'SettingController@systemSettingStore')->name('setting.system.store');
    Route::post('/form-setting/store', 'SettingController@formSettingStore')->name('setting.form.store');
    Route::post('/device-setting/store', 'SettingController@deviceSettingStore')->name('setting.device.store');
    Route::post('/device-setting/smsstore', 'SettingController@smsSettingStore')->name('setting.sms.store');
    Route::post('/device-setting/detail', 'SettingController@pacsDetail')->name('pacs.detail');
    Route::get('/device-setting', 'SettingController@deviceSetting')->name('setting.device');
    Route::get('/form-setting', 'SettingController@formSetting')->name('setting.form');
    Route::post('/template-save', 'SettingController@labPdfTemplateSave')->name('setting.template.save');
    Route::post('/opd-data-save', 'SettingController@saveOpdReport')->name('setting.opd.report.save');
    Route::post('/opd-data-save-data-dynamic', 'ReportController@saveData')->name('setting.opd.report.data.dynamic.save');

    Route::get('/billing-mode', 'SettingController@billingmode')->name('billing.mode');

    Route::get('/prefix-setting', 'SettingController@prefixsetting')->name('prefix.setting');
    Route::post('/add-billingmode', 'SettingController@addbillingmode')->name('add-billingmode');
    Route::post('/change-billingmode-status', 'SettingController@statusChangeBillingmode')->name('change.billingmode.status');
    Route::get('/deletebillingmode/{id}', 'SettingController@deletebillingmode')->name('deletebillingmode');

    Route::post('/updateprefix', 'SettingController@updateprefix')->name('updateprefix');

    Route::get('/deleteyear', 'SettingController@deleteyear')->name('deleteyear');
    Route::get('/register-setting', 'SettingController@registerSetting')->name('register-setting');

    Route::get('/registration-setting', 'SettingController@registrationSetting')->name('setting.registration');
    Route::post('/registration-setting/store', 'SettingController@registrationSettingStore')->name('setting.registration.store');
    Route::post('/redirect-last-encounter/store', 'SettingController@redirectLastEncounterStore')->name('setting.redirect-last-encounter.store');

    Route::get('/medicine-setting', 'SettingController@medicineSetting')->name('setting.medicine');
    Route::post('/medicine-setting/store', 'SettingController@medicineSettingStore')->name('setting.medicine.store');

    Route::post('/purchaseentry-setting/store', 'SettingController@purchaseentrySettingStore')->name('setting.purchaseentry.store');

    Route::get('/bed-setting', 'BedController@bedSetting')->name('setting.bed');
    Route::post('/bed-setting/typestore', 'BedController@bedtypeSettingStore')->name('setting.bedtype.store');
    Route::post('/bed-setting/type-delete', 'BedController@deleteBedType')->name('setting.bedtype.delete');

    Route::post('/bed-setting/groupstore', 'BedController@bedgroupSettingStore')->name('setting.bedgroup.store');
    Route::post('/bed-setting/group-delete', 'BedController@deleteBedGroup')->name('setting.bedgroup.delete');

    Route::post('/bed-setting/floorstore', 'BedController@bedfloorSettingStore')->name('setting.bedfloor.store');
    Route::post('/bed-setting/floor-delete', 'BedController@deleteBedFloor')->name('setting.bedfloor.delete');

    Route::get('/advertisement', 'AdvertisementController@index')->name('advertisement');
    Route::get('/advertisement/add', 'AdvertisementController@add')->name('advertisement.add');
    Route::post('/advertisement/store', 'AdvertisementController@store')->name('advertisement.store');
    Route::get('/advertisement/edit/{id}', 'AdvertisementController@edit')->name('advertisement.edit');
    Route::post('/advertisement/edit/{id}', 'AdvertisementController@update')->name('advertisement.update');
    Route::get('/advertisement/delete/{id}', 'AdvertisementController@delete')->name('advertisement.delete');


    Route::get('/ethnic', 'EthnicgroupController@index')->name('ethnic');
    Route::post('/store-ethnic', 'EthnicgroupController@ethnicStore')->name('store-ethnic');
    Route::post('/edit-ethnic', 'EthnicgroupController@ethnicUpdate')->name('edit-ethnic');
    Route::post('/delete-ethnic', 'EthnicgroupController@ethnicDelete')->name('delete-ethnic');
    Route::post('/search-ethnic', 'EthnicgroupController@searchEthnic')->name('search-ethnic');
    Route::post('/ethnic-saveorder', 'EthnicgroupController@saveOrder')->name('ethnic-saveorder');

    Route::get('/insurance', 'InsuranceController@index')->name('insurance');
    Route::post('/store-insurance', 'InsuranceController@insuranceStore')->name('store-insurance');
    Route::get('/insurance/chage-status', 'InsuranceController@changeStatus')->name('change-insurance-status');
    // Route::post('/edit-ethnic', 'EthnicgroupController@ethnicUpdate')->name('edit-ethnic');
    // Route::post('/delete-ethnic', 'EthnicgroupController@ethnicDelete')->name('delete-ethnic');
    // Route::post('/search-ethnic', 'EthnicgroupController@searchEthnic')->name('search-ethnic');
    // Route::post('/ethnic-saveorder', 'EthnicgroupController@saveOrder')->name('ethnic-saveorder');

    Route::get('/municipality-setting', 'MunicipalityController@municipalitySetting')->name('municipality');
    Route::get('/municipality/add', 'MunicipalityController@add')->name('municipality.add');
    Route::get('/municipality/edit/{id}', 'MunicipalityController@edit')->name('municipality.edit');
    Route::post('/municipality/store', 'MunicipalityController@store')->name('municipality.store');
    Route::post('/municipality/update/{id}', 'MunicipalityController@update')->name('municipality.update');
    Route::post('/municipality/delete', 'MunicipalityController@delete')->name('municipality.delete');

    Route::get('/hospital-branch-setting', 'HospitalBranchController@branchSetting')->name('hospital.branch');
    Route::get('/hospital-branch/add', 'HospitalBranchController@add')->name('hospital.branch.add');
    Route::get('/hospital-branch/edit/{id}', 'HospitalBranchController@edit')->name('hospital.branch.edit');
    Route::post('/hospital-branch/store', 'HospitalBranchController@store')->name('hospital.branch.store');
    Route::post('/hospital-branch/update/{id}', 'HospitalBranchController@update')->name('hospital.branch.update');
    Route::post('/hospital-branch/delete', 'HospitalBranchController@delete')->name('hospital.branch.delete');

    Route::get('/hospital-department-setting', 'HospitalDepartmentController@departmentSetting')->name('hospital.department');
    Route::get('/hospital-department/add', 'HospitalDepartmentController@add')->name('hospital.department.add');
    Route::get('/hospital-department/edit/{id}', 'HospitalDepartmentController@edit')->name('hospital.department.edit');
    Route::post('/hospital-department/store', 'HospitalDepartmentController@store')->name('hospital.department.store');
    Route::post('/hospital-department/update/{id}', 'HospitalDepartmentController@update')->name('hospital.department.update');
    Route::post('/hospital-department/delete', 'HospitalDepartmentController@delete')->name('hospital.department.delete');

    /*
     * Auto billing
     */
    Route::any('/auto-billing', 'AutoBillingController@index')->name('auto.billing');
    Route::post('/auto-billing/insert-update', 'AutoBillingController@insertUpdate')->name('auto.billing.insert.update');

    /*
     * form signature
     */
    Route::any('/form-signature/{formName?}', 'SignatureController@addEditFormSignature')->name('setting.signature.form');
    Route::post('/form-signature-insert', 'SignatureController@insertSignature')->name('setting.signature.insert');
    Route::post('/form-signature-new-select', 'SignatureController@appendSelectSignature')->name('setting.signature.append.select');

    Route::match(['get', 'post'], 'dispensing', 'SettingController@dispensingSetting')->name('setting.dispensing');

    Route::match(['get', 'post'], 'purchase-order', 'SettingController@purchaseOrderSetting')->name('setting.purchaseOrder');

    /**
     * fiscal year
     */
    Route::get('/fiscal-setting', 'FiscalYearController@fiscalyear')->name('fiscal.setting');
    Route::get('/fiscal-setting-edit/{fldname}', 'FiscalYearController@edit')->name('fiscal.setting.edit');
    Route::post('/updatefiscal', 'FiscalYearController@updatefiscal')->name('updatefiscal');
    Route::post('/add-fiscal-year', 'FiscalYearController@addFiscalYear')->name('add.fiscal.year');
    Route::get('/deletefiscalyear/{id}', 'FiscalYearController@deletefiscalyear')->name('deletefiscalyear');

    /**
     * Permission Setting
     */

    Route::get('/permission-setting', 'PermissionSettingController@index')->name('permission.setting');
    Route::post('/permission-setting-store', 'PermissionSettingController@store')->name('permission.setting.store');

    /**
     * Notification Setting
     */

    Route::get('/notification-setting', 'NotificationController@index')->name('notification.setting');
    Route::post('/notification-setting-store', 'NotificationController@settingSave')->name('notification.setting.store');

    /**Account Setting*/
    Route::get('/account-setting', 'AccountSettingController@index')->name('account.setting');
    Route::post('/account-setting-add', 'AccountSettingController@add')->name('account.setting.add');

    /**billing*/
    Route::get('/billing-setting', 'BillingSettingController@index')->name('billing.setting');
    Route::post('/billing-setting-save', 'BillingSettingController@saveSetting')->name('billing.setting.save');
    Route::post('/billing-toggle-save', 'BillingSettingController@saveBillingToggle')->name('billing.setting.save.toggle.billing');
    Route::post('/billing-discount-save', 'BillingSettingController@discountBilling')->name('billing.setting.save.discount.percent');
    Route::post('/billing-emergencyshare-save', 'BillingSettingController@saveEmergencyShareSetting')->name('billing.setting.save.emergencydrshare');
    Route::post('/billing-setting-type', 'BillingSettingController@typeSetting')->name('billing.setting.type');

    /**sidebar setting*/
    Route::get('/sidebar-setting', 'SidebarSettingController@index')->name('sidebar.menu');
    Route::get('/sidebar/add', 'SidebarSettingController@add')->name('sidebar.menu.add');
    Route::get('/sidebar/edit/{id}', 'SidebarSettingController@edit')->name('sidebar.menu.edit');
    Route::post('/sidebar/store', 'SidebarSettingController@store')->name('sidebar.menu.store');
    Route::post('/sidebar/update/{id}', 'SidebarSettingController@update')->name('sidebar.menu.update');
    Route::post('/sidebar/delete', 'SidebarSettingController@delete')->name('sidebar.menu.delete');
    Route::post('/sidebar/order', 'SidebarSettingController@updateOrder')->name('save.order.sidebar');

    /**IRD settings*/
    Route::get('/ird-setting', 'IrdController@setting')->name('ird.setting');
    Route::post('/ird/save', 'IrdController@saveIrd')->name('save.ird');

    /**
     * SSF Setting
     */
    Route::get('/ssf-setting', 'SsfSettingController@index')->name('ssf.setting');
    Route::post('/ssf-setting-store', 'SsfSettingController@settingSave')->name('ssf.setting.store');

    Route::post('/hi-setting-store', 'HIController@settingSave')->name('hi.setting.store');
    Route::post('/claim-setting-store', 'ClaimController@settingSave')->name('claim.setting.store');


});
