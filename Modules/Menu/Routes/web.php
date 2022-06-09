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

Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'menu'], function () {
    /*
     * File Menu
     */
    Route::post('display-waiting-form-file-menu', array(
        'as'   => 'patient.file.menu.waiting',
        'uses' => 'MenuController@displayWaitingForm'
    ));

    Route::post('display-waiting-result-file-menu', array(
        'as'   => 'patient.file.menu.waiting.result',
        'uses' => 'MenuController@waitingData'
    ));

    Route::post('display-search-form-file-menu', array(
        'as'   => 'patient.file.menu.search',
        'uses' => 'MenuController@displaySearchForm'
    ));

    Route::post('display-last-encounter-form-file-menu', array(
        'as'   => 'patient.last.encounter.form',
        'uses' => 'MenuController@lastEncounter'
    ));

    Route::post('display-inpatient-last-encounter-form-file-menu', array(
        'as'   => 'inpatient.last.encounter.form',
        'uses' => 'MenuController@lastEncounterInpatient'
    ));

    Route::post('display-delivery-last-encounter-form-file-menu', array(
        'as'   => 'delivery.last.encounter.form',
        'uses' => 'MenuController@lastEncounterDelivery'
    ));


    Route::post('display-eye-last-encounter-form-file-menu', array(
        'as'   => 'eye.last.encounter.form',
        'uses' => 'MenuController@lastEncounterEye'
    ));

    Route::post('display-major-last-encounter-form-file-menu', array(
        'as'   => 'major.last.encounter.form',
        'uses' => 'MenuController@lastEncounterMajor'
    ));

    Route::post('display-emergency-last-encounter-form-file-menu', array(
        'as'   => 'emergency.last.encounter.form',
        'uses' => 'MenuController@lastEncounterEmergency'
    ));
    Route::post('display-dental-last-encounter-form-file-menu', array(
        'as'   => 'dental.last.encounter.form',
        'uses' => 'MenuController@lastEncounterDental'
    ));

    Route::post('/last-encounter', array(
        'as'   => 'patient.last.encounter',
        'uses' => 'MenuController@setLastEncounter'
    ));

    Route::post('/last-encounter-inpatient', array(
        'as'   => 'inpatient.last.encounter',
        'uses' => 'MenuController@setLastEncounterInpatient'
    ));

    Route::post('/last-encounter-delivery', array(
        'as'   => 'delivery.last.encounter',
        'uses' => 'MenuController@setLastEncounterDelivery'
    ));


    Route::post('/last-encounter-emergency', array(
        'as'   => 'emergency.last.encounter',
        'uses' => 'MenuController@setLastEncounterEmergency'
    ));

    Route::post('/last-encounter-eye', array(
        'as'   => 'eye.last.encounter',
        'uses' => 'MenuController@setLastEncounterEye'
    ));

    Route::post('/last-encounter-dental', array(
        'as'   => 'dental.last.encounter',
        'uses' => 'MenuController@setLastEncounterDental'
    ));

    Route::post('/last-encounter-major', array(
        'as'   => 'major.last.encounter',
        'uses' => 'MenuController@setLastEncounterMajor'
    ));



    /*
    * Request Menu
    */

    Route::post('display-majorprocedure-form-request-menu', array(
        'as'   => 'patient.request.menu.majorprocedure',
        'uses' => 'RequestController@displayMajorProcedureForm'
    ));

    Route::post('display-extraprocedure-form-request-menu', array(
        'as'   => 'patient.request.menu.extraprocedure',
        'uses' => 'RequestController@displayExtraProcedureForm'
    ));

    Route::post('patient-request-menu-addextraprocedure', array(
        'as'   => 'patient.request.menu.addextraprocedure',
        'uses' => 'RequestController@addExtraProcedure'
    ));
    Route::post('patient-request-menu-deleteextraprocedure', array(
        'as'   => 'patient.request.menu.deleteextraprocedure',
        'uses' => 'RequestController@deleteExtraProcedure'
    ));

    Route::post('patient-request-menu-saveextraprocedure', array(
        'as'   => 'patient.request.menu.saveextraprocedure',
        'uses' => 'RequestController@saveExtraProcedure'
    ));

    Route::post('patient-request-menu-listplanneddata', array(
        'as'   => 'patient.request.menu.listplanneddata',
        'uses' => 'RequestController@listPlannedData'
    ));

    Route::post('patient-request-menu-addprocedure', array(
        'as'   => 'patient.request.menu.addprocedure',
        'uses' => 'RequestController@addProcedure'
    ));

    Route::post('patient-request-menu-editprocedure', array(
        'as'   => 'patient.request.menu.editprocedure',
        'uses' => 'RequestController@editProcedure'
    ));

    Route::post('patient-request-menu-deleteprocedure', array(
        'as'   => 'patient.request.menu.deleteprocedure',
        'uses' => 'RequestController@deleteProcedure'
    ));


    Route::post('patient-request-menu-englishtonepali', array(
        'as'   => 'patient.request.menu.englishtonepali',
        'uses' => 'RequestController@englishtonepali'
    ));

    Route::post('patient-request-menu-nepalitoenglish', array(
        'as'   => 'patient.request.menu.nepalitoenglish',
        'uses' => 'RequestController@nepalitoenglish'
    ));
    Route::get('/getProcedureByBilling', 'RequestController@getProcedureByBilling')->name('getProcedureByBilling');

    Route::get('/getMonitoringParticulars', 'RequestController@getMonitoringParticulars')->name('getMonitoringParticulars');

    Route::post('display-monitoring-form-request-menu', array(
        'as'   => 'patient.request.menu.monitoring',
        'uses' => 'RequestController@displayMonitoringForm'
    ));

    Route::post('patient-request-menu-addmonitor', array(
        'as'   => 'patient.request.menu.addmonitor',
        'uses' => 'RequestController@addMonitor'
    ));

    Route::post('patient-request-menu-deletemonitor', array(
        'as'   => 'patient.request.menu.deletemonitor',
        'uses' => 'RequestController@deleteMonitor'
    ));
    Route::post('patient-request-menu-pupulate', array(
        'as'   => 'patient.request.menu.pupulate',
        'uses' => 'RequestController@populateData'
    ));

    /*
     * End Request Menu
     */

    /*
     * Start Outcome Menu
     */
    Route::post('display-referto-form-outcome-menu', array(
        'as'   => 'patient.outcome.menu.referto',
        'uses' => 'OutcomeController@displayRefertoForm'
    ));

    Route::post('patient-outcome-menu-addreferto', array(
        'as'   => 'patient.outcome.menu.addreferto',
        'uses' => 'OutcomeController@addReferto'
    ));

    Route::post('display-followup-form-outcome-menu', array(
        'as'   => 'patient.outcome.menu.followup',
        'uses' => 'OutcomeController@displayFollowupForm'
    ));

    Route::post('patient-outcome-menu-updateFollowupdate', array(
        'as'   => 'patient.outcome.menu.updateFollowupdate',
        'uses' => 'OutcomeController@updateFollowupdate'
    ));
    /*
    * End Outcome Menu
    */
    Route::post('display-essenexam-form-dataentry-menu', array(
        'as'   => 'patient.dataentry.menu.essenenxam',
        'uses' => 'DataEntryController@essenexamForm'
    ));
    /*
     * Start DataEntry Menu
     */

    /*
     * End DataEntry Menu
     */
    Route::post('display-search-file-menu-result', array(
        'as'   => 'patient.file.menu.search.result',
        'uses' => 'MenuController@displaySearchResult'
    ));
    Route::post('display-search-file-menu-result-pdf', array(
        'as'   => 'patient.file.menu.search.result.pdf',
        'uses' => 'MenuController@exportSearchResultPdf'
    ));

    /*
     * History Menu
     */
    Route::get('patient-history-pdf-laboratory/{encounterId}', array(
        'as'   => 'patient.menu.history.pdf.laboratory',
        'uses' => 'HistoryMenuController@laboratoryHistoryPdf'
    ));
    Route::get('patient-history-pdf-radiology/{encounterId}', array(
        'as'   => 'patient.menu.history.pdf.radiology',
        'uses' => 'HistoryMenuController@radiologyHistoryPdf'
    ));
    Route::get('patient-history-pdf-medicine/{encounterId}', array(
        'as'   => 'patient.menu.history.pdf.medicine',
        'uses' => 'HistoryMenuController@medicineHistoryPdf'
    ));

    Route::any('patient-history-nav-encounter', array(
        'as'   => 'patient.menu.history.nav.encounter',
        'uses' => 'HistoryMenuController@historyEncounter'
    ));

    Route::any('patient-history-nav-selection', array(
        'as'   => 'patient.menu.history.nav.selection',
        'uses' => 'HistoryMenuController@selectionEncounter'
    ));

    Route::any('patient-history-nav-selection-generate-pdf', array(
        'as'   => 'patient.menu.history.nav.selection.generate.pdf',
        'uses' => 'HistoryMenuController@historySelectionGeneratePdf'
    ));

    /* Data view menu */

    Route::post('display-sample-form-dataview-menu', array(
        'as'   => 'patient.file.menu.labsample',
        'uses' => 'DataviewController@displaySampleForm'
    ));

    Route::get('patient-dataview-pdf-complete/{encounterId}', array(
        'as'   => 'patient.dataview.pdf.complete',
        'uses' => 'DataviewController@downloadcompleteLab'
    ));

    Route::post('patient-dataview-pdf-sample', array(
        'as'   => 'patient.dataview.pdf.sample',
        'uses' => 'DataviewController@downloadSampleLab'
    ));


    Route::get('patient-dataview-pdf-examination/{encounterId}', array(
        'as'   => 'patient.dataview.pdf.examination',
        'uses' => 'DataviewController@downloadExamination'
    ));


    /*
     * History Menu
     */
    Route::get('patient-history-pdf-laboratory/{encounterId?}', array(
        'as'   => 'patient.menu.history.pdf.laboratory',
        'uses' => 'HistoryMenuController@laboratoryHistoryPdf'
    ));
    Route::get('patient-history-pdf-radiology/{encounterId?}', array(
        'as'   => 'patient.menu.history.pdf.radiology',
        'uses' => 'HistoryMenuController@radiologyHistoryPdf'
    ));
    Route::get('patient-history-pdf-medicine/{encounterId?}', array(
        'as'   => 'patient.menu.history.pdf.medicine',
        'uses' => 'HistoryMenuController@medicineHistoryPdf'
    ));


    /*
     * consultation
     */
    Route::any('patient-menu-request-consultation', array(
        'as'   => 'patient.menu.request.consultation',
        'uses' => 'RequestController@displayConsultantForm'
    ));
    Route::any('patient-menu-request-consultation-add', array(
        'as'   => 'patient.menu.request.consultation.add',
        'uses' => 'RequestController@addConsultation'
    ));
    Route::any('patient-menu-request-consultation-delete', array(
        'as'   => 'patient.menu.request.consultation.delete',
        'uses' => 'RequestController@deleteConsultation'
    ));

    /*
     * Minor Procedure
     */
    Route::any('patient-menu-minor-procedure', array(
        'as'   => 'patient.minor.procedure.form',
        'uses' => 'DataEntryController@minorProcedureForm'
    ));

    Route::any('patient-menu-minor-procedure-add-waiting', array(
        'as'   => 'patient.menu.request.minor.procedure.waiting.add',
        'uses' => 'DataEntryController@listAddWaiting'
    ));

    Route::any('patient-menu-minor-procedure-add-cleared', array(
        'as'   => 'patient.menu.request.minor.procedure.cleared.add',
        'uses' => 'DataEntryController@listSaveCleared'
    ));
    Route::post('patient-menu-request-minor-procedure-delete', array(
        'as'   => 'patient.menu.request.minor.procedure.delete',
        'uses' => 'DataEntryController@deleteMinorProcedure'
    ));

    /*
     * Equipment
     */
    Route::any('patient-menu-equipment', array(
        'as'   => 'patient.minor.equipment.form',
        'uses' => 'DataEntryController@equipmentsForm'
    ));

    Route::any('patient-menu-equipment-add', array(
        'as'   => 'patient.equipment.form.add',
        'uses' => 'DataEntryController@addEquipment'
    ));

    Route::any('patient-menu-equipment-insert-start', array(
        'as'   => 'patient.equipment.form.insert.start',
        'uses' => 'DataEntryController@insertStartEquipment'
    ));

    Route::any('patient-menu-equipment-stop', array(
        'as'   => 'patient.equipment.form.stop',
        'uses' => 'DataEntryController@stopEquipment'
    ));

    Route::any('patient-menu-equipment-stop-complete-list', array(
        'as'   => 'patient.equipment.form.stop.complete',
        'uses' => 'DataEntryController@stopComplete'
    ));

    /*
     * Vaccination
     */
    Route::any('patient-menu-vaccination', array(
        'as'   => 'patient.vaccination.form',
        'uses' => 'DataEntryController@vaccinationForm'
    ));

    Route::any('patient-menu-vaccination-add', array(
        'as'   => 'patient.vaccination.add',
        'uses' => 'DataEntryController@vaccinationAdd'
    ));

    Route::any('patient-menu-variable', array(
        'as'   => 'patient.vaccination.variable.form',
        'uses' => 'DataEntryController@variableForm'
    ));

    Route::any('patient-menu-variable-add', array(
        'as'   => 'patient.vaccination.variable.add',
        'uses' => 'DataEntryController@variableAdd'
    ));

    Route::any('patient-menu-variable-delete', array(
        'as'   => 'patient.vaccination.variable.delete',
        'uses' => 'DataEntryController@variableDelete'
    ));

    Route::any('patient-menu-vaccination-edit', array(
        'as'   => 'patient.vaccination.edit',
        'uses' => 'DataEntryController@vaccinationEdit'
    ));

    //Expenses Menu
    Route::get('menu-expenses-laboratory-pdf/{encounterId?}', 'ExpensesController@laboratoryReportPdf')->name('menu.expenses.laboratory.pdfReport');
    Route::get('menu-expenses-radiology-pdf/{encounterId?}', 'ExpensesController@radiologyReportPdf')->name('menu.expenses.radiology.pdfReport');
    Route::get('menu-expenses-procedures-pdf/{encounterId?}', 'ExpensesController@proceduresReportPdf')->name('menu.expenses.procedures.pdfReport');
    Route::get('menu-expenses-general-services-pdf/{encounterId?}', 'ExpensesController@generalServicesReportPdf')->name('menu.expenses.general.services.pdfReport');
    Route::get('menu-expenses-equipment-pdf/{encounterId?}', 'ExpensesController@equipmentReportPdf')->name('menu.expenses.equipment.pdfReport');
    Route::get('menu-expenses-other-items-pdf/{encounterId?}', 'ExpensesController@otherItemsReportPdf')->name('menu.expenses.other.items.pdfReport');
    Route::get('menu-expenses-summary-pdf/{encounterId?}', 'ExpensesController@summaryReportPdf')->name('menu.expenses.summary.pdfReport');
    Route::get('menu-expenses-invoice-pdf/{encounterId?}', 'ExpensesController@invoiceReportPdf')->name('menu.expenses.invoice.pdfReport');





    Route::post('delivery-file-menu-history', array(
        'as'   => 'delivery.file.menu.history',
        'uses' => 'MenuController@patientEncounters'
    ));

    Route::post('call-waiting', array(
        'as'   => 'patient.file.menu.call.waiting',
        'uses' => 'MenuController@callwaiting'
    ));




    /*
    * Admission Request
    */
    Route::any('patient-menu-request-admission', array(
        'as'   => 'patient.menu.request.admission',
        'uses' => 'RequestController@displayAdmissionRequestForm'
    ));

    /*
   * Bladder  Irrigation
   */
    Route::any('bladder-irrigation/{encounter}', array(
        'as'   => 'patient.menu.bladder.irrigation',
        'uses' => 'DataviewController@bladderIrrigation'
    ));


});
