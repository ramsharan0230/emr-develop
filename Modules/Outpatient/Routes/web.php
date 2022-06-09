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

Route::group(['middleware' => ['web', 'auth-checker']], function () {

    Route::get('/reset-encounter', 'OutpatientController@resetEncounter')->name('reset.encounter');

    Route::any('patient', 'OutpatientController@index')->name('patient');

    //    Route::post('/patient/save', 'OutpatientController@index')->name('patient');

    Route::post('/consultants', 'OutpatientController@consultants')->name('consultants');


    /*Route::get('/admin', 'Backend\LoginController@showLoginForm')->name('admin.login')->middleware('guest');
    Route::post('/admin', 'Backend\LoginController@login')->middleware('guest');
    Route::get('/admin/logout', 'Backend\LoginController@logout')->name('admin.logout');*/

//    Route::get('/home', 'HomeController@index')->name('home');

    Route::get('/consults', 'OutpatientController@consults')->name('consults');

    Route::post('/autolist', 'OutpatientController@autolist')->name('autolist');

    Route::get('/pathology', 'OutpatientController@pathology')->name('pathology');

    Route::post('/autopathlist', 'OutpatientController@autopathlist')->name('autopathlist');

    Route::post('/allergydrugstore', 'AllergyController@insert_allergydrugstore')->name('allergydrugstore');
    Route::post('/deletepatfinding', 'AllergyController@deletepatfinding')->name('deletepatfinding');
    Route::get('/searchDrugs', 'AllergyController@searchDrugs')->name('searchDrugs');
    Route::get('/getAllDrugs', 'AllergyController@getAllDrugs')->name('getAllDrugs');

    Route::post('/diagnosisStore', 'DiagnosisController@diagnosisStore')->name('diagnosisStore');
    Route::post('/deletedaigopatfinding', 'DiagnosisController@deletedaigopatfinding')->name('deletedaigopatfinding');

    Route::get('/getDiagnosisByCodeSearch', 'DiagnosisController@getDiagnosisByCodeSearch')->name('getDiagnosisByCodeSearch');
    Route::get('/getDiagnosisByCode', 'DiagnosisController@getDiagnosisByCode')->name('getDiagnosisByCode');
    Route::get('/getDiagnosisByGroup', 'DiagnosisController@getDiagnosisByGroup')->name('getDiagnosisByGroup');
    Route::get('/getInitialDiagnosisCategoryAjax', 'DiagnosisController@getInitialDiagnosisCategoryAjax')->name('getInitialDiagnosisCategoryAjax');
    Route::get('/searchDiagnosis', 'DiagnosisController@searchDiagnosis')->name('searchDiagnosis');
    Route::post('/getEdddate', 'DiagnosisController@getEdddate')->name('getEdddate');

    Route::post('/insert_complaint', 'OutpatientController@insert_complaint')->name('insert_complaint');

    Route::post('/insert_essential_exam', 'OutpatientController@insert_essential_exam')->name('insert_essential_exam');
    Route::get('/essential_exam/get_essential_exam', 'OutpatientController@get_essential_exam');

    Route::get('/update_complaint/{id}', 'OutpatientController@update_complaint')->name('update_complaint');
    Route::post('/update_complaint', 'OutpatientController@update_complaint')->name('update_complaint');


    Route::post('/save_height', 'OutpatientController@save_height')->name('save_height');
    Route::post('/save_billingmode', 'OutpatientController@save_billingmode')->name('save_billingmode');
    Route::post('/save_weight', 'OutpatientController@save_weight')->name('save_weight');
    Route::post('/getAgeurl', 'OutpatientController@getAgeurl')->name('getAgeurl');


    Route::get('/delete_complaint/{id}', 'OutpatientController@delete_complaint')->name('delete_complaint');

    Route::post('/insert_complaint_detail', 'OutpatientController@insert_complaint_detail')->name('insert_complaint_detail');
    Route::post('/insert_finding_detail', 'OutpatientController@insert_finding_detail')->name('insert_finding_detail');

    Route::post('/insert_finding', 'OutpatientController@insert_finding')->name('insert_finding');

    Route::post('/update_abnormal', 'OutpatientController@update_abnormal')->name('update_abnormal');

    Route::post('/inside', 'OutpatientController@updateInside')->name('inside');

    Route::get('/delete_finding/{id}', 'OutpatientController@delete_finding')->name('delete_finding');

    Route::post('/save_note_tabs', 'OutpatientController@save_note_tabs')->name('save_note_tabs');

    Route::post('/get_encounter_number', 'OutpatientController@get_encounter_number')->name('get_encounter_number');
    Route::post('/get_latest_encounter_number', 'OutpatientController@get_latest_encounter_number')->name('get_latest_encounter_number');


    Route::post('/lnrsave', 'OutpatientController@lnrsave')->name('lnrsave');
    Route::post('/text_save', 'OutpatientController@text_save')->name('text_save');

    Route::post('/number_save', 'OutpatientController@number_save')->name('number_save');
    Route::post('/scale_save', 'OutpatientController@scale_save')->name('scale_save');


    Route::post('/get_content', 'OutpatientController@get_content')->name('get_content');


    Route::post('/store_drug', 'OutpatientController@storeDrugActivity')->name('store.drug');
    Route::post('/stop-fluid', 'OutpatientController@StopFluid')->name('stop.fluid');
    Route::post('/check_vital', 'OutpatientController@check_vitals')->name('check_vital');

    Route::post('/planned_consultant', 'OutpatientController@planned_consultant')->name('planned_consultant');
    Route::post('/save_consultant', 'OutpatientController@save_consultant')->name('save_consultant');
    Route::post('/save_refer_by', 'OutpatientController@save_refer_by')->name('save_refer_by');
    Route::post('patient-image-form', array(
        'as' => 'patient.image.form',
        'uses' => 'OutpatientController@getPhotographForm'
    ));

    Route::post('patient-image-form-save-waiting', array(
        'as' => 'patient.image.form.save.waiting',
        'uses' => 'OutpatientController@savePhotograph'
    ));

    Route::any('/set-room-number', 'OutpatientController@setroomno')->name('setroomno');
    Route::any('/set-hospital-department', 'OutpatientController@setHospitalDepartment')->name('setHospitalDepartment');

    /* Route for preview */
    Route::get('/outpatient/preview/{encounter_id}', 'OutpatientController@showOutpatientPreview')->name('outpatient.preview');

    /* Route for finsh button */
    Route::get('/outpatient/finish/box', 'OutpatientController@showFinishData')->name('outpatient.finish.box');
    /*
     * Laboratory routes
     */

    Route::post('display-laboratory-form', array(
        'as' => 'patient.laboratory.form',
        'uses' => 'LaboratoryOutPatientController@index'
    ));

    Route::post('display-laboratory-form-save-waiting', array(
        'as' => 'patient.laboratory.form.save.waiting',
        'uses' => 'LaboratoryOutPatientController@saveLaboratoryRequest'
    ));

    Route::post('display-laboratory-form-save-done', array(
        'as' => 'patient.laboratory.form.save.done',
        'uses' => 'LaboratoryOutPatientController@updateLaboratoryRequestDone'
    ));


    Route::post('list-laboratory-reported', array(
        'as' => 'patient.laboratory.list.laboratory.reported',
        'uses' => 'LaboratoryOutPatientController@labReported'
    ));

    Route::post('cancel-laboratory-reported', array(
        'as' => 'patient.laboratory.cancel.laboratory.reported',
        'uses' => 'LaboratoryOutPatientController@cancelRequest'
    ));

    Route::post('delete-laboratory-reported', array(
        'as' => 'patient.laboratory.delete.laboratory.requested',
        'uses' => 'LaboratoryOutPatientController@deleteRequest'
    ));

    Route::post('list-by-group-laboratory', array(
        'as' => 'patient.laboratory.request.list.by.group',
        'uses' => 'LaboratoryOutPatientController@listByGroup'
    ));

    Route::post('save-extra-lab', array(
        'as' => 'patient.laboratory.request.save.extra',
        'uses' => 'LaboratoryOutPatientController@saveExtraOrder'
    ));

    /*
     * radiology routes
     */


    Route::post('display-radiology-form', array(
        'as' => 'patient.radiology.form',
        'uses' => 'RadiologyOutPatientController@index'
    ));

    Route::post('display-radiology-form-save-waiting', array(
        'as' => 'patient.radiology.form.save.waiting',
        'uses' => 'RadiologyOutPatientController@saveRadiologyRequest'
    ));

    Route::post('display-radiology-form-save-done', array(
        'as' => 'patient.radiology.form.save.done',
        'uses' => 'RadiologyOutPatientController@updateRadiologyRequestDone'
    ));

    Route::post('list-radiology-reported', array(
        'as' => 'patient.radiology.list.radiology.reported',
        'uses' => 'RadiologyOutPatientController@radioReported'
    ));
    Route::post('cancel-radiology-reported', array(
        'as' => 'patient.radiology.cancel.radiology.reported',
        'uses' => 'RadiologyOutPatientController@cancelRequest'
    ));
    Route::post('delete-radiology-reported', array(
        'as' => 'patient.radiology.delete.radiology.reported',
        'uses' => 'RadiologyOutPatientController@deleteRequest'
    ));

    Route::post('list-by-group-radiology', array(
        'as' => 'patient.radiology.request.list.by.group',
        'uses' => 'RadiologyOutPatientController@listByGroup'
    ));

    Route::post('comment-request-radiology', array(
        'as' => 'patient.radiology.comment.request',
        'uses' => 'RadiologyOutPatientController@commentRequest'
    ));

    /*
     * Pharmacy
     */
    Route::post('display-pharmacy-form-new-order', array(
        'as' => 'patient.pharmacy.form.new.order',
        'uses' => 'PharmacyController@getMedicineListForNewOrders'
    ));

    Route::post('add-pharmacy-new-order', array(
        'as' => 'patient.pharmacy.add.new.order',
        'uses' => 'PharmacyController@queryNewOrderBeforeSave'
    ));

    Route::post('save-pharmacy-new-order', array(
        'as' => 'patient.pharmacy.save.new.order',
        'uses' => 'PharmacyController@saveNewOrder'
    ));


    Route::post('save-pharmacy-calculate-quantity', array(
        'as' => 'patient.pharmacy.calculate.quantity',
        'uses' => 'PharmacyController@calculateQuantity'
    ));


    Route::post('display-pharmacy-form', array(
        'as' => 'patient.pharmacy.form',
        'uses' => 'PharmacyController@index'
    ));

    Route::post('display-pharmacy-form.discharge', array(
        'as' => 'patient.pharmacy.form.discharge',
        'uses' => 'PharmacyController@discharge'
    ));

    Route::post('delete-pharmacy-order', array(
        'as' => 'patient.pharmacy.form.delete',
        'uses' => 'PharmacyController@deletePharmacyOrder'
    ));

    Route::post('pharmacy-order-date-change', array(
        'as' => 'patient.pharmacy.form.new.order.date.change',
        'uses' => 'PharmacyController@changeNewOrderDateForm'
    ));

    Route::post('pharmacy-order-change-update', array(
        'as' => 'patient.pharmacy.form.new.order.change.update',
        'uses' => 'PharmacyController@changeNewOrderUpdate'
    ));

    Route::post('pharmacy-order-dose-change', array(
        'as' => 'patient.pharmacy.form.new.order.dose',
        'uses' => 'PharmacyController@changeNewOrderDoseForm'
    ));

    Route::post('pharmacy-order-day-change', array(
        'as' => 'patient.pharmacy.form.new.order.day',
        'uses' => 'PharmacyController@changeNewOrderDayForm'
    ));

    Route::post('pharmacy-order-quantity-change', array(
        'as' => 'patient.pharmacy.form.new.order.quantity',
        'uses' => 'PharmacyController@changeNewOrderQtydispForm'
    ));

    Route::post('pharmacy-order-frequency-change', array(
        'as' => 'patient.pharmacy.form.new.order.frequency',
        'uses' => 'PharmacyController@changeNewOrderFrequencyForm'
    ));

    Route::post('pharmacy-order-direct-dispensing', array(
        'as' => 'patient.pharmacy.form.new.direct.dispensing',
        'uses' => 'PharmacyController@directDispensing'
    ));

    Route::post('pharmacy-selection-listing', array(
        'as' => 'patient.pharmacy.selection',
        'uses' => 'PharmacyController@listSelection'
    ));

    Route::post('pharmacy-add-comment', array(
        'as' => 'patient.pharmacy.add.comment',
        'uses' => 'PharmacyController@addComment'
    ));

    Route::post('pharmacy-reorder', array(
        'as' => 'patient.pharmacy.form.reorder.form',
        'uses' => 'PharmacyController@reorderMedicine'
    ));

    Route::post('pharmacy-reorder-bulk', array(
        'as' => 'patient.pharmacy.form.reorder.bulk',
        'uses' => 'PharmacyController@reorderBulk'
    ));

    Route::post('pharmacy-extra-order-save', array(
        'as' => 'patient.pharmacy.form.extra.order.save',
        'uses' => 'PharmacyController@saveExtraOrder'
    ));
    Route::post('pharmacy-extra-order-delete', array(
        'as' => 'patient.pharmacy.form.extra.order.delete',
        'uses' => 'PharmacyController@deleteExtraOrder'
    ));

    /*
     * General Services routes
     */

    Route::post('display-services-form', array(
        'as' => 'patient.services.form',
        'uses' => 'GeneralServicesOutPatientController@index'
    ));

    Route::post('display-services-form-save-waiting', array(
        'as' => 'patient.services.form.save.waiting',
        'uses' => 'GeneralServicesOutPatientController@saveServicesRequest'
    ));

    Route::post('display-ip-round-form-save-waiting', array(
        'as' => 'patient.ip-round.form.save.waiting',
        'uses' => 'GeneralServicesOutPatientController@saveIpRoundRequest'
    ));

    Route::post('ip-round-save-doc-share', array(
        'as' => 'patient.ip-round.form.save.doc-share',
        'uses' => 'GeneralServicesOutPatientController@saveDocShare'
    ));

    Route::post('ip-round-remove-doc-share', array(
        'as' => 'patient.ip-round.form.remove.doc-share',
        'uses' => 'GeneralServicesOutPatientController@removeDocShare'
    ));

    Route::post('display-services-form-save-done', array(
        'as' => 'patient.services.form.save.done',
        'uses' => 'GeneralServicesOutPatientController@updateServicesRequestDone'
    ));


    Route::post('list-services-reported', array(
        'as' => 'patient.services.list.services.reported',
        'uses' => 'GeneralServicesOutPatientController@servicesReported'
    ));

    Route::post('cancel-services-reported', array(
        'as' => 'patient.services.cancel.services.reported',
        'uses' => 'GeneralServicesOutPatientController@cancelRequest'
    ));

    Route::post('delete-services-reported', array(
        'as' => 'patient.services.delete.services.requested',
        'uses' => 'GeneralServicesOutPatientController@deleteRequest'
    ));

    Route::post('delete-ip-round-reported', array(
        'as' => 'patient.services.delete.ip-round.requested',
        'uses' => 'GeneralServicesOutPatientController@deleteIpRoundRequest'
    ));

    Route::post('list-by-group-services', array(
        'as' => 'patient.services.request.list.by.group',
        'uses' => 'GeneralServicesOutPatientController@listByGroup'
    ));

    /*
     * PDF functions
     */
    Route::any('outpatient/pdf-generate/{encounterId}', array(
        'as' => 'outpatient.pdf.generate.opd.sheet',
        'uses' => 'PdfController@generatePdf'
    ));


    /*
     * History functions
     */
    Route::any('outpatient/history-generate/{patientId}', array(
        'as' => 'outpatient.history.generate',
        'uses' => 'HistoryController@historypdf'
    ));

    Route::any('outpatient/history-complete-generate/{patientId}', array(
        'as' => 'outpatient.history.complete',
        'uses' => 'HistoryController@completepdf'
    ));


    /*
     * Diagnosis Controller
     */
    Route::post('display-obstetric-form', array(
        'as' => 'patient.diagnosis.obstetric',
        'uses' => 'DiagnosisController@getObstetricData'
    ));

    Route::post('display-obstetric-freetextform', array(
        'as' => 'patient.diagnosis.freetext',
        'uses' => 'DiagnosisController@getDiagnosisfreetext'
    ));
    Route::post('display-diagnosis-freetext-save-waiting', array(
        'as' => 'patient.diagnosis.freetext.save.waiting',
        'uses' => 'DiagnosisController@saveDiagnosisCustom'
    ));

    Route::post('display-allergy-freetextform', array(
        'as' => 'patient.allergy.freetext',
        'uses' => 'AllergyController@getAllergyfreetext'
    ));


    Route::post('display-allergy-freetext-save-waiting', array(
        'as' => 'patient.allergy.freetext.save.waiting',
        'uses' => 'AllergyController@saveAllergyCustom'
    ));


    Route::post('display-obstetric-form-save-waiting', array(
        'as' => 'patient.obstetric.form.save.waiting',
        'uses' => 'DiagnosisController@saveObstetricRequest'
    ));

    Route::post('convert-english-to-nepali', array(
        'as' => 'patient.date.convert',
        'uses' => 'DiagnosisController@englishtonepali'
    ));

    Route::post('convert-nepali-to-english', array(
        'as' => 'patient.nepalidate.convert',
        'uses' => 'DiagnosisController@nepalitoenglish'
    ));

    Route::post('display-opdhistory-form', array(
        'as' => 'patient.opdhistory.form',
        'uses' => 'OutpatientController@opdHistory'
    ));

    Route::get('/history', 'OutpatientController@history')->name('history');

    Route::post('/close_finish', 'OutpatientController@close_finish')->name('close_finish');

    // IPD ROUND
    Route::post('display-ipd-round-form', array(
        'as' => 'patient.ipd-round.form',
        'uses' => 'GeneralServicesOutPatientController@ipdRoundForm'
    ));

    Route::post('admit-patient', array(
        'as' => 'admit-patient',
        'uses' => 'OutpatientController@admitPatient'
    ));

    /**EEG form*/
    Route::post('eeg-save', array(
        'as' => 'eeg.save',
        'uses' => 'EegController@saveEeg'
    ));

    Route::get('/eeg/print/{encounter}/{id}', 'EegController@print')->name('eeg.print');
});
