<?php
Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'majorprocedure'], function () {
    Route::match(['get', 'post'], '/', 'MajorProcedureController@getMajorProcedureIndex')->name('majorprocedure');
    Route::post('/major_proceduer_get_encounter_number', 'MajorProcedureController@get_encounter_number')->name('majorproceduer.get.encounter.number');
    Route::post('/profile-save-height', 'MajorProcedureController@saveHeight')->name('majorprocedure.save.height');
    Route::post('/profile-save-weight', 'MajorProcedureController@saveWeight')->name('majorprocedure.save.weight');
    Route::post('/profile-getAgeurl', 'MajorProcedureController@getAgeurl')->name('majorprocedure.getAgeUrl');
	Route::post('/profile-save-consultant', 'MajorProcedureController@saveConsultant')->name('inpatient.save.consultant');
	Route::get('/reset-encounter', 'MajorProcedureController@resetEncounter')->name('reset.inpatient.encounter');
	// pre-operative
	// discussion
	Route::post('/pre-operative/preOperativeDiscussion', 'MajorProcedureController@preOperativeDiscussion')->name('insert.preOperativeDiscussion.freetext');
	Route::get('/pre-operative/getPreOperativeDiscussion', 'MajorProcedureController@getPreOperativeDiscussion');
	Route::post('/pre-operative/preOperativeDiscussionTxtArea', 'MajorProcedureController@preOperativeDiscussionTxtArea')->name('insert.preOperativeDiscussion.textarea');
	Route::get('/pre-operative/getPreOperativeDiscussionTxtArea', 'MajorProcedureController@getPreOperativeDiscussionTxtArea');
	Route::get('/pre-operative/getPatFinding', 'MajorProcedureController@getPatFinding');
	// clinical note
	Route::post('/pre-operative/clinical-indication', 'MajorProcedureController@insertClinicalIndication')->name('insert.clinicalIndication.clinicalNote');
	Route::get('/pre-operative/get-clinical-indication', 'MajorProcedureController@getClinicalIndication');
	Route::post('/pre-operative/clinical-note', 'MajorProcedureController@insertClinicalNote')->name('insert.clinicalNote.textarea');
	Route::get('/pre-operative/get-clinical-note', 'MajorProcedureController@getClinicalNote');
	// new porcedure
	Route::post('/new-procedure/update', 'MajorProcedureController@updateNewProcedure')->name('update.newProcedure.newProcedure');
	Route::get('/new-procedure/getSelectedData', 'MajorProcedureController@getSelectedData');
	Route::get('/new-procedure/reload-table', 'MajorProcedureController@reloadTable');
	Route::post('/new-procedure/insertFreetext', 'MajorProcedureController@insertNewProcedureFreeText')->name('insert.newProcedure.freetext');
	Route::get('/new-procedure/getComponents', 'MajorProcedureController@getComponents');
	Route::post('/new-procedure/insertVariables', 'MajorProcedureController@insertVariables')->name('insert.newProcedure.variables');
	Route::get('/new-procedure/getVariables', 'MajorProcedureController@getVariables');
	Route::post('/new-procedure/deleteVariables', 'MajorProcedureController@deleteVariables')->name('delete.newProcedure.variables');

	Route::post('/new-procedure/insertAnaesthesiaVariables', 'MajorProcedureController@insertAnaesthesiaVariables')->name('insert.anaeshtesia.variables');
	Route::get('/new-procedure/getAnaesthesiaVariables', 'MajorProcedureController@getAnaesthesiaVariables');
	Route::post('/new-procedure/deleteAnasethesiaVariables', 'MajorProcedureController@deleteAnaesthesiaVariables')->name('delete.anaeshtesia.variables');

	Route::get('/new-procedure/getDetails', 'MajorProcedureController@getDetails');
	Route::post('/new-procedure/insertDetails', 'MajorProcedureController@insertDetails')->name('insert.flddetail.newProcedure');

	Route::get('/new-procedure/getInitialProcedureCategoryAjaxs', 'MajorProcedureController@getInitialProcedureCategoryAjaxs');
	Route::get('/new-procedure/getProcedureByCodes', 'MajorProcedureController@getProcedureByCodes');
	Route::get('/new-procedure/getProcedureByCodes', 'MajorProcedureController@getProcedureByCodes');

	Route::post('/personnel/insertPersonnel', 'MajorProcedureController@insertPersonnel')->name('insert.personnel');
	Route::get('/personnel/getData', 'MajorProcedureController@getData');
	Route::get('/operation/other-items-select', 'MajorProcedureController@getSelectedItems');
	Route::post('/operation/insertOtherItems', 'MajorProcedureController@insertOtherItems')->name('insert.otherItems');
	Route::get('/operation/getOtherItemsData', 'MajorProcedureController@getOtherItemsData');

	// pre-operative phramacy
	Route::get('/pre-operative/phramacy/show-all', 'MajorProcedureController@getAllPhramacy');
	Route::get('/post-operative/phramacy/show-all', 'MajorProcedureController@getAllPhramacyPostOp');
	Route::get('/operation/phramacy/show-all', 'MajorProcedureController@getAllPhramacyOperation');
	Route::get('/anaesthesia/phramacy/show-all', 'MajorProcedureController@getAllPhramacyAnaesthesia');

	// ListAllExamination
	Route::get('/getExaminationList', 'MajorProcedureExaminationController@getExaminationLists');
	// GetExamonationModal
	Route::get('/getModalContent', 'MajorProcedureExaminationController@getModalContent');
	// GetExaminationModal
	Route::post('/savePatientExaminations', 'MajorProcedureExaminationController@savePatientExaminations');
	// GEtExaminationTable
	Route::get('/getPatientExaminations', 'MajorProcedureExaminationController@getPatientExaminations');

	// DisplayInTableExamination
	Route::get('/pre-operative/getExaminationData', 'MajorProcedureController@getExaminationData');
	Route::get('/post-operative/getExaminationData', 'MajorProcedureController@getExaminationData');
	Route::get('/operation/getExaminationData', 'MajorProcedureController@getExaminationData');
	Route::get('/anaesthesia/getExaminationData', 'MajorProcedureController@getExaminationData');

	// Report Phramacy
	Route::get('/phramacy-pdf/{encounterId?}', 'PdfController@phramacyPDF')->name('phramacy.pdfReport');

	Route::get('/reset-encounter', 'MajorProcedureController@resetEncounter')->name('major.reset.encounter');
	Route::post('/savePreAnaesthesia', 'MajorProcedureController@savePreAnaesthesia')->name('savePreAnaesthesia');
	Route::post('/saveIntraOperativeDetail', 'MajorProcedureController@saveIntraOperativeDetail')->name('saveIntraOperativeDetail');

    // OT Checklist
    Route::get('/get-ot-checklist-data', 'MajorProcedureController@getOtChecklistData')->name('major.ot-checklist.data');
	Route::post('/saveOtSignin', 'MajorProcedureController@saveOtSignin')->name('saveOtSignin');
    Route::post('/saveOtTimeout', 'MajorProcedureController@saveOtTimeout')->name('saveOtTimeout');
    Route::post('/saveOtSignout', 'MajorProcedureController@saveOtSignout')->name('saveOtSignout');

    //Pre-anaethestic evaluation
    Route::post('/save-preanaethestic-evaluation', 'MajorProcedureController@savePreAnaethesticEvaluation')->name('major.save.preanaethestic.evaluation');
});

Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'painmanagement'], function () {

    Route::match(['get', 'post'], '/', 'PainManagementController@index')->name('painmanagement');

    Route::post('/savePaindetail', 'PainManagementController@savePaindetail')->name('savePaindetail');

});
