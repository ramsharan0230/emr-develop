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

Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'emergency/'], function () {
    Route::any('/', 'EmergencyController@index')->name('emergency');
    // Insert, Edit, Delete Complaint
    Route::post('complaint/insert_complaint', 'EmergencyController@insert_complaint')->name('insert_complaint_emergency');
    Route::get('complaint/delete_complaint/{id}', 'EmergencyController@delete_complaint')->name('delete_complaint_emergency');
    Route::post('complaint/insert_complaint_detail', 'EmergencyController@insert_complaint_detail')->name('insert_complaint_detail_emergency');
    // History Tabs
    Route::post('history/save_note_tabs_emergency', 'EmergencyController@save_note_tabs')->name('save_note_tabs_emergency');
    // Essential Exam
    Route::post('essential_exam/insert_essential_exam', 'EmergencyController@insert_essential_exam')->name('insert_essential_exam_emergency');
    Route::get('essential_exam/get_essential_exam', 'EmergencyController@get_essential_exam');
    // Allergy
    // FreeText
    Route::post('allergy/display-allergy-freetextform', 'EmergencyController@getAllergyfreetext')->name('emergency.allergy.freetext');
    Route::post('allergy/display-allergy-freetext-save-waiting', 'EmergencyController@saveAllergyCustom')->name('emergency.allergy.freetext.save.waiting');
    // allergyModal
    Route::post('allergy/allergydrugstore', 'EmergencyController@insert_allergydrugstore')->name('emergency.allergydrugstore');
    // Search Drugs
    Route::get('allergy/searchDrugs', 'EmergencyController@searchDrugs')->name('emergency.searchDrugs');
    Route::get('allergy/getAllDrugs', 'EmergencyController@getAllDrugs')->name('emergency.getAllDrugs');
    // Delete Drugs
    Route::post('allergy/deletepatfinding', 'EmergencyController@deletepatfinding')->name('emergency.deletepatfinding');
    // Diagnosis
    // FreeText
    Route::post('diagnosis/display-obstetric-freetextform', 'EmergencyController@getDiagnosisfreetext')->name('emergency.diagnosis.freetext');
    Route::post('diagnosis/display-diagnosis-freetext-save-waiting', 'EmergencyController@saveDiagnosisCustom')->name('emergency.diagnosis.freetext.save.waiting');
    // ObstetricForm
    Route::post('diagnosis/display-obstetric-form', 'EmergencyController@getObstetricData')->name('emergency.diagnosis.obstetric');
    Route::post('diagnosis/display-obstetric-form-save-waiting', 'EmergencyController@saveObstetricRequest')->name('emergency.obstetric.form.save.waiting');
    // DiagnosisModal
    Route::post('diagnosis/diagnosisStore', 'EmergencyController@diagnosisStore')->name('emergency.diagnosisStore');
    Route::get('diagnosis/getDiagnosisByCode', 'EmergencyController@getDiagnosisByCode')->name('emergency.getDiagnosisByCode');
    Route::get('diagnosis/getDiagnosisByGroup', 'EmergencyController@getDiagnosisByGroup')->name('emergency.getDiagnosisByGroup');
    Route::get('diagnosis/getInitialDiagnosisCategoryAjax', 'EmergencyController@getInitialDiagnosisCategoryAjax')->name('emergency.getInitialDiagnosisCategoryAjax');
    // Examination Clinical Finding
    Route::post('examination/get_content', 'EmergencyController@get_content')->name('get_content_emergency');
    Route::post('examination/lnrsave', 'EmergencyController@lnrsave')->name('lnrsave_emergency');
    Route::post('examination/text_save', 'EmergencyController@text_save')->name('text_save_emergency');
    Route::post('examination/number_save', 'EmergencyController@number_save')->name('number_save_emergency');
    Route::post('examination/scale_save', 'EmergencyController@scale_save')->name('scale_save_emergency');
    Route::get('examination/delete_finding/{id}', 'EmergencyController@delete_finding')->name('emergency_delete_finding');
    // Referre Location
    Route::post('referre/insertLocation', 'EmergencyController@referre_location')->name('insert.referre.location.emergency');
    // Notes
    Route::get('notes/getRelatedNote', 'EmergencyController@getRelatedNote');
    Route::post('notes/updateNote', 'EmergencyController@updateNote')->name('update.note.emergency');
    Route::get('notes/get-all-notes', 'EmergencyController@getAllNotes');
    // Department
    Route::get('department-bed/get-related-bed', 'EmergencyController@getRelatedBed');
    Route::get('department-bed/{encounterVal}/get-encounter-related-bed', 'EmergencyController@getEncounterRelatedBed')->name('encounter.department-beds');
    Route::post('department-bed/save-bed', 'EmergencyController@postDepartmentBed')->name('save.department.bed');
    Route::post('department-bed/update-bed', 'EmergencyController@updateDepartmentBed')->name('update.department.bed');
    Route::get('department-locat/get-related-locat', 'EmergencyController@getDepartmentLocation');
    Route::post('update/patient-admission', 'EmergencyController@postPatientAdmission')->name('update.patient.fldadmission');
    Route::get('get/patient-admission', 'EmergencyController@getPatientAdmittionStatus');
    // Triage Color Change
    Route::post('triage/change-color', 'EmergencyController@changeColor')->name('update.triage.color');
    Route::get('triage/get-related-color', 'EmergencyController@getColor');
    Route::post('check_vital_emergency', 'EmergencyController@check_vital_emergency')->name('check_vital_emergency');

    Route::post('/update_abnormal', 'EmergencyController@update_abnormal')->name('er_update_abnormal');
    Route::get('/reset-encounter', 'EmergencyController@resetEncounter')->name('emergency.reset.encounter');

    Route::post('savepain', 'EmergencyController@save_pain')->name('save.pain');
    Route::post('insert_general_exam', 'EmergencyController@insert_general_exam')->name('insert_general_exam');
    Route::post('insert_gcs', 'EmergencyController@insert_gcs')->name('insert_gcs');

    Route::any('emergency/pdf-generate/{encounterId}', array(
        'as'   => 'emergency.pdf.generate.opd.sheet',
        'uses' => 'EmergencyController@generatePdf'
    ));

});
