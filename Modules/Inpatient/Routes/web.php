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

Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => ''], function () {
	Route::prefix('inpatient')->group(function() {
	    Route::match(['get', 'post'], '/', 'InpatientController@getInpatientView')->name('inpatient');
	    Route::match(['get','post'], '/get_encounter_number_inpatient', 'InpatientController@get_encounter_number')->name('get_encounter_number_inpatient');
		Route::get('/dashboard', 'InpatientController@dashboard')->name('dashboard');
	    Route::post('/ajax-delete-provisional/', 'InpatientController@deleteProvisional')->name('delete.provisional');
	    Route::post('/ajax-delete-final/', 'InpatientController@deleteFinal')->name('delete.final');
	    Route::post('/ajax-delete-allergic/', 'InpatientController@deleteAllergic')->name('delete.allergic');
	    Route::get('/getDiagnosisByGroup/', 'InpatientController@getDiagnosisByGroup')->name('getDiagnosisByGroups');
	    Route::get('/getInitialDiagnosisCategoryAjaxs/', 'InpatientController@getInitialDiagnosisCategoryAjaxs')->name('getInitialDiagnosisCategoryAjaxs');
	    Route::get('/getDiagnosisByCodes/', 'InpatientController@getDiagnosisByCodes')->name('getDiagnosisByCodes');
	    Route::post('/diagnosisStoreInpatient', 'InpatientController@diagnosisStore')->name('diagnosisStoreInpatient');
	    Route::post('/finalDiagnosisStore', 'InpatientController@finalDiagnosisStore')->name('finalDiagnosisStoreInpatient');
	    Route::post('/display-obstetric-freetext-form', 'InpatientController@getDiagnosisfreetext')->name('inpatient.diagnosis.freetext');
		Route::post('/display-obstetric-freetext-save-waiting', 'InpatientController@saveDiagnosisCustom')->name('inpatient.diagnosis.freetext.save.waiting');
	    Route::post('/display-obstetric-freetext-form-final', 'InpatientController@getFinalDiagnosisfreetext')->name('inpatient.diagnosis.freetext.final');
	    Route::post('/display-final-obstetric-freetext-save-waiting', 'InpatientController@saveDiagnosisCustom')->name('inpatient.final.diagnosis.freetext.save.waiting');
	    Route::post('/display-obstetric-form', 'InpatientController@getObstetricData')->name('inpatient.diagnosis.obstetric');
	    Route::post('/display-obstetric-form-save-waiting', 'InpatientController@saveObstetricRequest')->name('inpatient.obstetric.form.save.waiting');
	    Route::post('/display-final-obstetric-form', 'InpatientController@getFinalObstetricData')->name('inpatient.diagnosis.final.obstetric');
	    Route::post('/display-final-obstetric-form-save-waiting', 'InpatientController@saveFinalObstetricRequest')->name('inpatient.final.obstetric.form.save.waiting');
	    Route::get('/searchDrugs', 'InpatientController@searchDrugs')->name('inpatient.searchDrugs');
	    Route::get('/getAllDrugs', 'InpatientController@getAllDrugs')->name('inpatient.getAllDrugs');
	    Route::post('/allergydrugstore', 'InpatientController@insert_allergydrugstore')->name('allergydrugstoreInpatient');
		Route::get('/delete_complaint/{id}', 'PresentController@delete_complaint')->name('present.delete_complaint');
		Route::post('inpatient-image-form', 'InpatientController@getPhotographForm')->name('inpatient.image.form');
		Route::post('inpatient-image-form-save-waiting', 'InpatientController@savePhotograph')->name('inpatient.image.form.save.waiting');
		Route::post('/inpatient_save_height', 'InpatientController@save_height')->name('inpatient.save.height');
	    Route::post('/inpatient_save_weight', 'InpatientController@save_weight')->name('save.weight.inpatient');
	    Route::post('/getAgeurlInpatient', 'InpatientController@getAgeurl')->name('get.Age.url.inpatient');
		Route::post('/inpatient-save-consultant', 'InpatientController@saveConsultant')->name('inpatient.save.consultant');

		Route::get('/reset-encounter', 'InpatientController@resetEncounter')->name('reset.inpatient.encounter');
		Route::post('/change-inOut-chart', 'InpatientController@getInOutGraphData')->name('change.inOut.chart');
		Route::post('/change-blood-pressure-chart', 'InpatientController@getBloodPressureData')->name('change.blood-pressure.chart');
		//otchecklist routes
        Route::get('/new-procedure/getComponents', 'InpatientController@getComponents');
        Route::get('/new-procedure/getDetails', 'InpatientController@getDetails');
        Route::get('/get-ot-checklist-data', 'InpatientController@getOtChecklistData')->name('inpatient.ot-checklist.data');
        Route::post('/saveOtSignin', 'InpatientController@saveOtSignin')->name('inpatient.saveOtSignin');
        Route::post('/saveOtTimeout', 'InpatientController@saveOtTimeout')->name('inpatient.saveOtTimeout');
        Route::post('/saveOtSignout', 'InpatientController@saveOtSignout')->name('inpatient.saveOtSignout');

        //Pre-anaethestic evaluation
        Route::post('/save-preanaethestic-evaluation', 'InpatientController@savePreAnaethesticEvaluation')->name('inpatient.save.preanaethestic.evaluation');



	});

	// OnExaminations Routes
	Route::group([
		'prefix' => 'inpatient/onexamination/',
	], function() {
		// examinations
		Route::get('/getModalContent', 'OnExaminationController@getModalContent');
		Route::get('/getExaminationsOptions', 'OnExaminationController@getExaminationsOptions');
		Route::get('/getExaminations', 'OnExaminationController@getExaminations');
		Route::post('/saveExamination', 'OnExaminationController@saveExamination');
		Route::post('/deleteExamination', 'OnExaminationController@deleteExamination');
		Route::post('/changeOnExamStatus', 'OnExaminationController@changeOnExamStatus');

		// Weights
		Route::get('/getWeights', 'OnExaminationController@getWeights');
		Route::post('/saveWeight', 'OnExaminationController@saveWeight');
	});

	// In/Out Routes
	Route::group([
		'prefix' => 'inpatient/inout/',
	], function() {
		Route::get('/getInOutListData', 'InOutController@getInOutListData');

		// Out Fluid
		// Route::get('getOutFluidList', 'InOutController@getOutFluidList');
		Route::get('/getOutFluid', 'InOutController@getOutFluid');
		Route::post('/saveOutFluid', 'InOutController@saveOutFluid');

		Route::get('/getMedicineList', 'InOutController@getMedicineList');
		Route::post('/updateDoseRate', 'InOutController@updateDoseRate');
		Route::post('/updateVolumn', 'InOutController@updateVolumn');

		// Diet Planning
		Route::get('/getDiets', 'InOutController@getDiets');
		Route::get('/getTypeData', 'InOutController@getTypeData');
		Route::get('/getTypeItems', 'InOutController@getTypeItems');

		Route::post('/deleteDiet', 'InOutController@deleteDiet');

		Route::post('/addDailyDietPlan', 'InOutController@addDailyDietPlan');
		Route::post('/saveDailyDietPlan', 'InOutController@saveDailyDietPlan');

		Route::post('/setComplete', 'InOutController@setComplete');
		Route::post('/saveIntake', 'InOutController@saveIntake');

		Route::post('/updateExtraDosing', 'InOutController@updateExtraDosing');
		Route::get('/getDietsPdf', 'InOutController@getDietsPdf');
	});

	// Present
	Route::prefix('present')->group(function() {
		Route::post('/postCauseDetail', 'PresentController@postCauseDetail')->name('inpatient.store.cause');
		Route::get('/postCauseDetail', 'PresentController@getCauseDetail');
		Route::get('/ajax-related-history/', 'PresentController@get_related_history');
		Route::post('/insertPresentHistory/', 'PresentController@insertPresentHistory')->name('present.history.save');
		Route::post('/insert-complaint-inpresent', 'PresentController@insertComplain')->name('inpatient.insert.complaint');
		Route::post('/insert-complaint-detail-inpatient', 'PresentController@insertComplaintDetail')->name('insert.complaint.detail');
		Route::get('/delete-complaint-inpatient/{id}', 'PresentController@deleteComplaint')->name('delete_complaint');
		Route::post('/insert-complaint-duration', 'PresentController@insertComplaintDuration')->name('insert.complaint.duration');
		Route::post('/insert-complaint-side', 'PresentController@insertComplaintSide')->name('insert.complaint.side');
	});

	// Labs Routes
	Route::group([
		'prefix' => 'inpatient/labs/',
	], function() {
		Route::get('/getQuantiQualiData', 'LabsController@getQuantiQualiData');
		Route::get('/getTestsData', 'LabsController@getTestsData');
	});

	// Progress Routes
	Route::group([
		'prefix' => 'inpatient/prog/',
	], function() {
		Route::get('/getTime', 'ProgController@getTime');
		Route::get('/getTimeData', 'ProgController@getTimeData');
		Route::post('/addTime', 'ProgController@addTime');
		Route::post('/addTextData', 'ProgController@addTextData');
		Route::get('/getExaminationSelectData', 'ProgController@getExaminationSelectData');
		Route::get('/getExaminationSelectOptions', 'ProgController@getExaminationSelectOptions');
		Route::post('/saveExaminationData', 'ProgController@saveExaminationData');

		Route::get('/getColor', 'ProgController@getColor');
		Route::get('/getEssentialExamination', 'ProgController@getEssentialExamination');
		Route::post('/changeColor', 'ProgController@changeColor');

		Route::get('/getEssentialList', 'ProgController@getEssentialList');
		Route::get('/essence-line-chart', 'ProgController@essenceLineChart');
		Route::post('/saveEssential', 'ProgController@saveEssential');

		Route::post('/readtextfile', 'ProgController@readtextfile');
	});

	// Fluids Routes
	Route::group([
		'prefix' => 'inpatient/fluids/',
	], function() {
		Route::get('/getFluids', 'FluidsController@getFluids');
		Route::post('/updateFluidData', 'FluidsController@updateFluidData');
		Route::get('/getFluidParticulars', 'FluidsController@getFluidParticulars');
		Route::post('/changeFluidStatus', 'FluidsController@changeFluidStatus');
		Route::post('/saveParticulars', 'FluidsController@saveParticulars');
		Route::post('/stopParticular', 'FluidsController@stopParticular');

		Route::get('/getCompatibilityInformation', 'FluidsController@getCompatibilityInformation');
		Route::get('/generatePDF', 'FluidsController@generatePDF');
	});

	// Plan Routes
	Route::group([
		'prefix' => 'inpatient/plan/',
	], function() {
		Route::get('/getPlans', 'PlanController@getPlans');
		Route::post('/saveUpdatePlan', 'PlanController@saveUpdatePlan');
	});

	// General Routes
	Route::group([
		'prefix' => 'inpatient/general/',
	], function() {
		Route::get('/getSymptomsList', 'GeneralController@getSymptomsList');
		Route::get('/getPatientSymptoms', 'GeneralController@getPatientSymptoms');
		Route::post('/saveSymptoms', 'GeneralController@saveSymptoms');
		Route::post('/updateSymptoms', 'GeneralController@updateSymptoms');
		Route::post('/changeSymptomStatus', 'GeneralController@changeSymptomStatus');
		Route::post('/deleteSymptoms', 'GeneralController@deleteSymptoms');
		Route::post('/resolveSymptom', 'GeneralController@resolveSymptom');

		Route::get('/getStatus', 'GeneralController@getStatus');
		Route::post('/saveStatus', 'GeneralController@saveStatus');
	});

	// Notes
	Route::group([
		'prefix' => 'inpatient/notes/',
	], function() {
		Route::post('/insert', 'NotesController@postInsertNotes')->name('inpatient.insert.note');
		Route::post('/update', 'NotesController@postUpdateNotes')->name('inpatient.update.note');
		Route::get('/ajax-related-list', 'NotesController@listOnSelect');
		Route::get('/ajax-date-list', 'NotesController@listOnDate');
		Route::get('/ajax-list-all', 'NotesController@listAll');
		Route::post('/refere-patient', 'NotesController@postReferePatient')->name('inpatient.refere.patient');
	});

	// Routine
	Route::group([
		'prefix' => 'inpatient/routine/',
	], function() {
		Route::get('/ajax-list-all', 'RoutineController@onclick');
		Route::get('/ajax-show-all', 'RoutineController@listall');
		Route::get('/show-medicine-routine', 'RoutineController@showMedicineRoutine');
		Route::get('/show-medicine-details', 'RoutineController@showMedicineDetails');
		Route::get('/radio', 'RoutineController@showValue');
		Route::get('/ajax-date-list', 'RoutineController@listByDate');
		Route::get('/get_status', 'RoutineController@getStatus');
		Route::post('/change_status', 'RoutineController@changeStatus')->name('update.routine.status');
		Route::post('/change_days', 'RoutineController@changeDays')->name('update.routine.days');
		Route::get('/get-changed-day', 'RoutineController@getChangeDays');
		Route::get('/get-changed-status', 'RoutineController@getChangeStatus');

	});

	// Stat
	Route::group([
		'prefix' => 'inpatient/stat/',
	], function() {
		Route::get('/ajax-list-all', 'StatsController@onclick');
		Route::get('/ajax-show-all', 'StatsController@listall');
		Route::get('/show-medicine-stat', 'StatsController@showMedicineStat');
		Route::get('/show-medicine-details', 'StatsController@showMedicineDetails');
		Route::get('/radio', 'StatsController@showValue');
		Route::get('/ajax-date-list', 'StatsController@listByDate');
		Route::get('/get_status', 'StatsController@getStatus');
		Route::post('/change_status_stat', 'StatsController@changeStatus')->name('update.stat.status');
		Route::post('/change_days_stat', 'StatsController@changeDays')->name('update.stat.days');
		Route::get('/get-changed-day', 'StatsController@getChangeDays');
		Route::get('/get-changed-status', 'StatsController@getChangeStatus');


		Route::get('/getDosingRecord', 'StatsController@getDosingRecord');
		Route::get('/getDosingDetail', 'StatsController@getDosingDetail');
		Route::post('/addDosingDetail', 'StatsController@addDosingDetail');
		Route::post('/updateDosingDetail', 'StatsController@updateDosingDetail');

		Route::get('/generateLabelPDF', 'StatsController@generateLabelPDF');
		Route::get('/generateDrugInfoPDF', 'StatsController@generateDrugInfoPDF');
		Route::get('/generateReviewPDF', 'StatsController@generateReviewPDF');
		Route::get('/generateExportMedicineDetailPDF', 'StatsController@generateExportMedicineDetailPDF');
	});

	// Data View Routes
	Route::group([
		'prefix' => 'inpatient/dataview/',
		'as' => 'dataview.'
	], function() {
		Route::get('/transitions', 'DataViewMenuController@transitions')->name('transitions');
		Route::get('/symtoms', 'DataViewMenuController@symtoms')->name('symtoms');
		Route::get('/poInputs', 'DataViewMenuController@poInputs')->name('poInputs');
		Route::get('/exams', 'DataViewMenuController@exams')->name('exams');
		Route::get('/radiology', 'DataViewMenuController@radiology')->name('radiology');
		Route::get('/laboratory', 'DataViewMenuController@laboratory')->name('laboratory');
		Route::get('/notes', 'DataViewMenuController@notes')->name('notes');
		Route::get('/diagnosis', 'DataViewMenuController@diagnosis')->name('diagnosis');
		Route::get('/medDosing', 'DataViewMenuController@medDosing')->name('medDosing');
		Route::get('/progress', 'DataViewMenuController@progress')->name('progress');
		Route::get('/planning', 'DataViewMenuController@planning')->name('planning');

		Route::get('/medReturn', 'DataViewMenuController@medReturn')->name('medReturn');
		Route::get('/nurActivity', 'DataViewMenuController@nurActivity')->name('nurActivity');
		Route::get('/complete', 'DataViewMenuController@complete')->name('complete');
        Route::get('/ot-checklists-report', 'DataViewMenuController@otChecklistsReport')->name('ot.checklist-report');
        Route::get('/preanaethestic-evaluation-report', 'DataViewMenuController@preAnaethesticEvaluationReport')->name('preanaethestic.evaluation.report');


	});

	// Data Entry Routes
	Route::group([
		'prefix' => 'inpatient/dataEntryMenu/',
		'as' => 'dataentry.'
	], function() {
		Route::get('/getTriageExam', 'DataEntryMenuController@getTriageExam')->name('getTriageExam');
		Route::get('/getModalContent', 'DataEntryMenuController@getModalContent')->name('getModalContent');
		Route::post('/saveTriageExam', 'DataEntryMenuController@saveTriageExam')->name('saveTriageExam');

		Route::get('/reportClinicalDemographics', 'DataEntryMenuController@reportClinicalDemographics')->name('reportClinicalDemographics');
		Route::get('/getClinicalDemographics', 'DataEntryMenuController@getClinicalDemographics')->name('getClinicalDemographics');
		Route::post('/saveClinicalDemographics', 'DataEntryMenuController@saveClinicalDemographics')->name('saveClinicalDemographics');

		Route::get('/getPatientImage', 'DataEntryMenuController@getPatientImage')->name('getPatientImage');
		Route::post('/savePatientImage', 'DataEntryMenuController@savePatientImage')->name('savePatientImage');
		Route::post('/updatePatientImage', 'DataEntryMenuController@updatePatientImage')->name('updatePatientImage');
	});

	// Express Routes
	Route::group([
		'prefix' => 'inpatient/expressMenu/',
		'as' => 'express.'
	], function() {
		Route::get('/getExpressPdf', 'ExpressMenuController@getExpressPdf')->name('getExpressPdf');
	});

	// Certificte Routes
	Route::group([
		'prefix' => 'inpatient/certificateMenu/',
		'as' => 'certificate.'
	], function() {

		Route::get('/{id}/{certificate}', 'CertificateMenuController@generatePdf')->name('generate');

	});

	// Outcome
	Route::group([
		'prefix' => 'inpatient/outcome/',
	], function() {
		Route::post('/insert-discharge', 'OutComesController@insertDischargePatient')->name('outcome.discharge.save');
		Route::post('/insert-lama', 'OutComesController@insertLamaPatient')->name('outcome.lama.save');
		Route::post('/insert-refere', 'OutComesController@insertReferePatient')->name('outcome.refere.save');
		Route::post('/insert-death', 'OutComesController@insertDeathPatient')->name('outcome.death.save');
		Route::post('/insert-absconder', 'OutComesController@insertAbsconderPatient')->name('outcome.absconder.save');
	});


    // Inpatient List
    Route::group([
        'prefix' => 'inpatient/inpatient-list/',
    ], function() {
        Route::get('/', 'InpatientListController@index')->name('inpatient.inpatient.list');
        Route::get('/search', 'InpatientListController@search')->name('inpatient.inpatient.search');
        Route::get('/get-related-bed', 'InpatientListController@getRelatedBed')->name('inpatient.inpatient.bed');
        Route::get('department-locat/get-related-locat', 'InpatientListController@getDepartmentLocation');
        Route::get('/undo-discharge', 'InpatientListController@undoDischarge')->name('inpatientlist.undo.discharge');
    });
});
