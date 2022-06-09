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
	'prefix' => 'registrationform',
], function() {
    Route::match(['get', 'post'], '/', 'RegistrationController@index')->name('registrationform');
	Route::get('list', 'RegistrationController@list')->name('registrationform.list');
	Route::post('previous-registration', 'RegistrationController@previousRegistration')->name('previous.registration');
	Route::get('getProvinces', 'RegistrationController@getProvinces');
	Route::get('getDistricts/{id}', 'RegistrationController@getDistricts');
	Route::get('getMunicipalities/{id}', 'RegistrationController@getMunicipalities');
	Route::get('getPatientDetailByPatientId/', 'RegistrationController@getPatientDetailByPatientId');
	Route::get('getRegistrationCost', 'RegistrationController@getRegistrationCost');
	Route::get('getDepatrmentUser', 'RegistrationController@getDepatrmentUser');

	Route::get('printcard/{id}', 'RegistrationController@printcard')->name('printcard');
	Route::get('printticket/{id}', 'RegistrationController@printticket')->name('print.ticket');
	Route::get('printnextticket/{id}', 'RegistrationController@printnextticket')->name('print.nextticket');
	Route::get('printband/{id}', 'RegistrationController@printband')->name('print.band');
	Route::get('print-bar-code/{id}', 'RegistrationController@printBarCode')->name('print.bar.code');

	Route::get('getSurname', 'RegistrationController@getSurname')->name('getSurname');
	Route::post('addSurname', 'RegistrationController@addSurname')->name('addSurname');
	Route::post('deleteSurname', 'RegistrationController@deleteSurname')->name('deleteSurname');

	Route::get('getDepartments', 'RegistrationController@getDepartments')->name('registrationform.getDepartments');
	Route::get('getDiscmode', 'RegistrationController@getDiscmode')->name('registrationform.getDiscmode');

	Route::get('registrationCsv', 'RegistrationController@registrationCsv')->name('registrationform.registrationCsv');
	Route::get('registrationPdf', 'RegistrationController@registrationPdf')->name('registrationform.registrationPdf');

	Route::get('getOldPatientDetail', 'RegistrationController@getOldPatientDetail');

	Route::get('idcard', 'RegistrationController@idcard')->name('registrationform.idcard');

	Route::get('get-discount-percent', 'DiscountDataController@getDiscountPercent')->name('registrationform.getDiscountPercent');

	Route::get('syncUserShare', 'RegistrationController@syncUserShare');

    Route::match(['get', 'post'], '/update-consultation', 'RegistrationController@UpdateConsultantList')->name('update-consultant-list');
	Route::get('/get-edit-consultation', 'RegistrationController@getConsultantByEncounter')->name('get-edit-consultant-list');

	Route::get('/checkeligibility','RegistrationController@checkeligibility')->name('check.hi.eligibility');
});

Route::group([
    'middleware' => ['web', 'auth-checker'],
    'prefix' => 'rank',
], function() {
    Route::match(['get', 'post'],'/', 'RankRegistrationController@index')->name('report.register');
    Route::get('/patient-details/{id}', 'RankRegistrationController@getPatientDetails')->name('report.patient.details');
    Route::get('/getMunicipalities/{id}', 'RankRegistrationController@getMunicipalities')->name('report.patient.getMunicipalities');
    Route::get('/getDistricts/{id}', 'RankRegistrationController@getDistricts')->name('report.patient.getDistricts');
    Route::get('/getProvinces', 'RankRegistrationController@getProvinces')->name('report.patient.getProvinces');
    Route::get('/getRegistrationCost', 'RankRegistrationController@getRegistrationCost')->name('report.patient.getRegistrationCost');
    Route::get('/getDepatrmentUser', 'RankRegistrationController@getDepatrmentUser')->name('report.patient.getDepatrmentUser');
    Route::get('/getDepartments', 'RankRegistrationController@getDepartments')->name('report.registrationform.getDepartments');
    Route::get('/getSurname', 'RankRegistrationController@getSurname')->name('report.registrationform.getSurname');
    Route::post('/addSurname', 'RankRegistrationController@addSurname')->name('report.registrationform.addSurname');
    Route::post('/deleteSurname', 'RankRegistrationController@deleteSurname')->name('report.registrationform.deleteSurname');
});
