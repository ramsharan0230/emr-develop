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
Route::any('/dashboard-new', 'AdminDashboardController@newdashboard')->name('admin.dashboard.new');
Route::group(['middleware' => ['auth-checker'], 'prefix' => 'admin/dashboard'], function()
{
    Route::any('/', 'AdminDashboardController@index')->name('admin.dashboard');

    Route::post('/male-female-chart', 'AdminDashboardController@maleFemaleChart')->name('admin.dashboard.male.female.chart');
    Route::post('/patient-by-department', 'AdminDashboardController@patientByDepartment')->name('admin.dashboard.patient.department.chart');
    Route::post('/patient-by-billing-mode', 'AdminDashboardController@patientByBillingMode')->name('admin.dashboard.patient.billing.mode.chart');
    Route::post('/patient-by-OPD', 'AdminDashboardController@getPatientByOPD')->name('admin.dashboard.patient.opd.chart');
    Route::post('/patient-by-IPD', 'AdminDashboardController@getPatientByIPD')->name('admin.dashboard.patient.ipd.chart');
    Route::post('/bed-occupacy-details', 'AdminDashboardController@getBedOccupacyDetails')->name('admin.dashboard.bed-occupacy');
    Route::post('/patient-by-Emergency', 'AdminDashboardController@getPatientByEmergency')->name('admin.dashboard.patient.emergency.chart');
    Route::post('/lab-status', 'AdminDashboardController@labStatus')->name('admin.dashboard.lab.status.chart');
    Route::post('/lab-order-status', 'AdminDashboardController@labOrderStatus')->name('admin.dashboard.lab.order-status.chart');
    Route::post('/radiology-status', 'AdminDashboardController@radiologyStatus')->name('admin.dashboard.radiology.status.chart');
    Route::post('/radiology-order-status', 'AdminDashboardController@radiologyOrderStatus')->name('admin.dashboard.radiology.order-status.chart');
    Route::post('/lab-newold-patient', 'AdminDashboardController@labNewOldPatient')->name('admin.dashboard.lab.newold-patient.chart');
    Route::post('/radio-newold-patient', 'AdminDashboardController@radioNewOldPatient')->name('admin.dashboard.radio.newold-patient.chart');
    Route::post('/radio-inpatient-outpatient', 'AdminDashboardController@radioInpatientOutpatientPatient')->name('admin.dashboard.radio.inpatient-outpatient.chart');
    Route::post('/pharmacy/op-sales', 'AdminDashboardController@opSales')->name('admin.dashboard.pharmacy.op-sales.chart');
    Route::post('/pharmacy/ip-sales', 'AdminDashboardController@ipSales')->name('admin.dashboard.pharmacy.ip-sales.chart');

    Route::get('/access-forbidden', array(
        'as' => 'access-forbidden',
        'uses' => 'AdminDashboardController@accessForbidden'
    ));

    Route::get('doctor-revenue-filter', 'AdminDashboardController@doctorshare')->name('admin.dashboard.doctorshare-filter');
});


Route::group(['middleware' => ['auth-checker'], 'prefix' => 'v2/dashboard'], function()
{
    Route::get('/fiscal-year', 'DashboardVueController@fiscalYear');
    Route::get('/in-out-emergency', 'DashboardVueController@patientCount');
    Route::get('/new-old-follow-up', 'DashboardVueController@newOldPatientCount');
    Route::get('/online-walking', 'DashboardVueController@onlineWalking');
    Route::get('/ot-count', 'DashboardVueController@otCount');
    Route::get('/delivery-count', 'DashboardVueController@deliveryCount');
    Route::get('/pharmacy-count', 'DashboardVueController@pharmacyCount');
    Route::get('/current-inpatient', 'DashboardVueController@currentInpatient');
    Route::get('/death', 'DashboardVueController@deathCount');
    Route::get('/lab-details', 'DashboardVueController@labDetails');
    Route::get('/radio-details', 'DashboardVueController@radioDetails');
    Route::post('/age-wise-details', 'DashboardVueController@ageWiseDetails');
    Route::any('/revenue-details', 'DashboardVueController@revenueDetails');
    Route::get('/radiology-reports', 'DashboardVueController@radiologyReports');
    Route::get('/lab-reports', 'DashboardVueController@labReports');
});
