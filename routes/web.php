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


// Route::get('testbarcode/ffddgff', function(){
// 	// dd('dgfd');
// 	return view('testbarcode')->render();
// })->name('testprintxx.js');

// Route::get('', function(){
// 	return view('testprint');
// })->name('testprint.js');

Route::middleware('auth-checker')->group(function () {
    Route::group(['prefix' => 'test','as'=>'test.'], function () {
		Route::get('/menus', "TestController@menus")->name('menus');
    });
});


Route::get('/clear', function () {
	Artisan::call('cache:clear');
	Artisan::call('config:cache');
	Artisan::call('view:clear');
	Artisan::call('route:clear');
	// return what you want
	dd('cache cleared');
});
//Route::get('/', 'Frontend\LoginController@showLoginForm')->middleware('guest');
/*Route::get('/admin', 'Backend\LoginController@showLoginForm')->name('admin.login')->middleware('guest');
Route::get('/login', 'Frontend\LoginController@showLoginForm')->name('frontend.login')->middleware('guest');
//Route::post('/admin', 'Backend\LoginController@login')->name('user.login')->middleware('guest');
Route::post('/login/submit', 'Frontend\UserController@submit')->name('frontend.login.submit');
Route::post('/admin', 'Backend\UserController@submit');*/
//Route::get('/admin/logout', 'Frontend\LoginController@logout')->name('admin.logout');
Route::get('/', function () {
	return redirect()->route('cogent.login.form');
});

/*patient login*/
Route::get('patient-portal/login', 'PatientLogin@loginForm')->name('patient.portal.login.show.form');
Route::post('patient-portal/submit', array(
	'as' => 'patient.portal.login.submit',
	'uses' => 'PatientLogin@login'
));
Route::get('patient-portal/logout', 'PatientLogin@logout')->name('patient.portal.logout');
/*patient login*/

// header search patient
Route::any('/search-patient', 'PatientController@searchPatient')->name('search-patient');
Route::get('/search-patient-new', 'SearchController@searchPatient')->name('search-patient-new');
Route::get('/get-patient', 'SearchController@getPatient')->name('get-patient-new');
// header search patient

//Route::any('/opdNeuro', 'PatientController@opdNeuro')->name('opdNeuro');

Route::get('/pooja-query/{encounter}', 'PoojaController@downloadExamination');



Route::get('/elekhacategory', 'PoojaController@category');
Route::get('/elekhasubcategorymedicine', 'PoojaController@subcategorymedicine');
Route::get('/elekhasubcategorysurgical', 'PoojaController@subcategorysurgical');
Route::get('/elekhasubcategoryextra', 'PoojaController@subcategoryextra');
Route::get('/elekhaitem_create', 'PoojaController@item_create');
Route::get('/elekhacreate_units', 'PoojaController@create_units');
Route::get('/elekhacreate_warehouse', 'PoojaController@create_warehouse');

/* machine interfacing route*/
Route::get('/machine-interface-hematology', 'MachineInterfacingController@hematologyInterfacing')->name('machine.interfacing.hematology');
Route::get('/machine-interface-biochem-siemens', 'MachineInterfacingController@biochemInterfacing')->name('machine.interfacing.biochem');
Route::get('/machine-interface-cobas', 'MachineInterfacingController@cobasInterfacing')->name('machine.interfacing.cobas');




Route::get('/machine-interface-department', 'MachineInterfacingController@machineInterfacingDepartment')->name('machine.interfacing.department');
Route::get('/machine-parse-hematology', 'MachineInterfacingController@parse_data_hematology')->name('machine.interfacing.hematology');
Route::get('/machine-parse-biochem', 'MachineInterfacingController@parse_data_biochem_siemens')->name('machine.interfacing.biochem');
Route::get('/machine-check/{sample_id}', 'MachineInterfacingController@checkCodes')->name('machine.interfacing.checkCodes');
Route::get('/machine-codes/{sample_id}', 'MachineInterfacingController@machineCodes')->name('machine.interfacing.machineCodes');

Route::group([
	'prefix' => 'newcogent'
], function () {
	Route::get('pre-delivery', function () {
		return view('newcogent.pre-delivery');
	});
	Route::get('dietary-info', function () {
		return view('newcogent.dietary-info');
	});
	Route::get('major-procedure', function () {
		return view('newcogent.major-procedure');
	});
	Route::get('extra-procedure', function () {
		return view('newcogent.extra-procedure');
	});
	Route::get('modal', function () {
		return view('newcogent.modal');
	});
	Route::get('HMIS', function () {
		return view('newcogent.HMIS');
	});
	Route::get('clinical-exam', function () {
		return view('newcogent.clinical-exam');
	});
	Route::get('ER', function () {
		return view('newcogent.ER');
	});
	Route::get('dental', function () {
		return view('newcogent.dental');
	});
	Route::get('Test-Addition', function () {
		return view('newcogent.Test-Addition');
	});
	Route::get('purchase', function () {
		return view('newcogent.purchase');
	});
	Route::get('modal2', function () {
		return view('newcogent.modal2');
	});
	Route::get('report', function () {
		return view('newcogent.report');
	});
	Route::get('stock', function () {
		return view('newcogent.stock');
	});
	Route::get('i', function () {
		return view('newcogent.pdf');
	});

	Route::get('niwesh', function () {
		return view('newcogent.niwesh');
	});
});

Route::get('/global/getExamObservationModal', 'Frontend\GlobalController@getExamObservationModal')->name('frontend.getExamObservationModal');
Route::post('/global/updateExamObservation', 'Frontend\GlobalController@updateExamObservation')->name('frontend.updateExamObservation');

/*copy users data from old table to laravel role permission users table*/
Route::get('/new-users/transfer-data', 'UserDataTransferController@copyUsersData')->name('frontend.transfer.users.data');


// New design routes

Route::get('new/login', function () {
	return view('new/login');
});
Route::get('new/dashboard', function () {
	return view('new/dashboard');
});
Route::get('department', function () {
	return view('new.department');
})->name('new.department');

Route::get('discount-mode', function () {
	return view('new.discount-mode');
})->name('new.discount-mode');

// costing
Route::get('otheritem', function () {
	return view('new.otheritem');
})->name('new.otheritem');

Route::get('general-service', function () {
	return view('new.general-service');
})->name('new.general-service');

Route::get('equipment', function () {
	return view('new.equipment');
})->name('new.equipment');

Route::get('procedure', function () {
	return view('new.procedure');
})->name('new.procedure');

Route::get('radio', function () {
	return view('new.radio');
})->name('new.radio');

Route::get('laboratoryrate', function () {
	return view('new.laboratoryrate');
})->name('new.laboratoryrate');

Route::get('inventory', function () {
	return view('new.inventory');
})->name('new.inventory');


Route::get('fiscal', function () {
	return view('new.fiscal');
})->name('new.fiscal');

Route::get('variables', function () {
	return view('new.variables');
})->name('new.variables');

Route::get('prefix', function () {
	return view('new.prefix');
})->name('new.prefix');

Route::get('registration', function () {
	return view('new.registration');
})->name('new.registration');

Route::get('regist', function () {
	return view('new.regist');
})->name('new.regist');

Route::get('dispenser', function () {
	return view('new.dispenser');
})->name('new.dispenser');

Route::get('dispensing', function () {
	return view('new.dispensing');
})->name('new.dispensing');

Route::get('return', function () {
	return view('new.return');
})->name('new.return');

Route::get('despensingForm', function () {
	return view('new.despensingForm');
})->name('new.despensingForm');

Route::get('popup', function () {
	return view('new.popup');
})->name('new.popup');

Route::get('planreport', function () {
	return view('new.planreport');
})->name('new.planreport');

/*Route::get('followup', function () {
	return view('new.followup'); })->name('new.followup');*/

Route::get('cashier', function () {
	return view('new.cashier');
})->name('new.cashier');

Route::get('userShare', function () {
	return view('new.userShare');
})->name('new.userShare');

Route::get('extraReception', function () {
	return view('new.extraReception');
})->name('new.extraReception');

Route::get('section', function () {
	return view('new.section');
})->name('new.section');
Route::get('band', function () {
	return view('new.band');
})->name('new.band');

Route::get('dashboard1', function () {
	return view('new.dashboard1');
})->name('new.dashboard1');
Route::get('routine', function () {
	return view('new.routine');
})->name('new.routine');

// bhuwan route

Route::get('stafflist', function () {
	return view('bhuwan.stafflist');
})->name('bhuwan.stafflist');

Route::get('storageCoding', function () {
	return view('bhuwan.storageCoding');
})->name('bhuwan.storageCoding');

Route::get('stockAdjustment', function () {
	return view('bhuwan.stockAdjustment');
})->name('bhuwan.stockAdjustment');

Route::get('purchaseEntry', function () {
	return view('bhuwan.purchaseEntry');
})->name('bhuwan.purchaseEntry');

Route::get('stockTransfer', function () {
	return view('bhuwan.stockTransfer');
})->name('bhuwan.stockTransfer');

Route::get('stockConsume', function () {
	return view('bhuwan.stockConsume');
})->name('bhuwan.stockConsume');

Route::get('stockView', function () {
	return view('bhuwan.stockView');
})->name('bhuwan.stockView');

Route::get('purchaseorder', function () {
	return view('new.purchaseorder');
})->name('new.purchaseorder');

//Route::get('opdNeuro', function () {
//	return view('new.opdNeuro'); })->name('new.opdNeuro');

Route::get('audiogram', function () {
	return view('new.audiogram');
})->name('new.audiogram');

Route::get('dischargepdf', function () {
	return view('new.discharge');
})->name('new.discharge');
Route::get('opdpdf', function () {
	return view('new.opd');
})->name('new.opd');

Route::get('plainmgt', function () {
	return view('new.plainmgt');
})->name('new.plainmgt');

// bhuwan route
Route::get('behalf', function () {
	return view('report.behalf');
})->name('report.behalf');

Route::get('patientregister', function () {
	return view('report.patientregister');
})->name('report.patientregister');

Route::get('pdfreport', function () {
	return view('report.pdfreport');
})->name('report.pdfreport');

Route::get('employes', function () {
	return view('report.employes');
})->name('report.employes');

//Route::get('register', function () {
//	return view('report.register'); })->name('report.register');

Route::get('billing', function () {
	return view('report.billing');
})->name('report.billing');

Route::get('collection', function () {
	return view('report.collection');
})->name('report.collection');

Route::get('signin', function () {
	return view('report.signin');
})->name('report.signin');

// TODO: remove this.
// test route for xdebug
// Route::get('/test', function() {
//     $a = 234;
//     $b = 123123123;
//     $sum = $a + $b;

//     return response([$sum]);
// });

Route::get('accountgroup', function () {
	return view('report.accountgroup');
})->name('report.accountgroup');

Route::get('subgroup', function () {
	return view('report.subgroup');
})->name('report.subgroup');

Route::get('subhead', function () {
	return view('report.subhead');
})->name('report.subhead');

Route::get('transaction', function () {
	return view('report.transaction');
})->name('report.transaction');

Route::get('ledger', function () {
	return view('report.ledger');
})->name('report.ledger');

Route::get('statement', function () {
	return view('report.statement');
})->name('report.statement');

Route::get('daybook', function () {
	return view('report.daybook');
})->name('report.daybook');

Route::get('profitloss', function () {
	return view('report.profitloss');
})->name('report.profitloss');

Route::get('trailbalance', function () {
	return view('report.trailbalance');
})->name('report.trailbalance');

Route::get('balancesheet', function () {
	return view('report.balancesheet');
})->name('report.balancesheet');

Route::get('appoitment', function () {
	return view('report.appoitment');
})->name('report.appoitment');

Route::get('appoitment1', function () {
	return view('report.appoitment1');
})->name('report.appoitment1');

Route::get('logtransaction', function () {
	return view('report.logtransaction');
})->name('report.logtransaction');


Route::get('idcard', function () {
	return view('report.idcard');
})->name('report.idcard');

Route::get('donar', function () {
	return view('report.donar');
})->name('report.donar');

Route::get('patHistory', function () {
	return view('report.patHistory');
})->name('report.patHistory');

Route::get('list', function () {
	return view('report.list');
})->name('report.list');


Route::get('noraForm', function () {
	return view('report.noraForm');
})->name('report.noraForm');

Route::any('/bed-status', 'BedController@index')->name('bed-status');
