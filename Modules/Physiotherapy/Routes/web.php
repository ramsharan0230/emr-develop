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

Route::group(['middleware' => ['web' => 'auth-checker'], 'prefix' => 'physiotherapy'], function() {
    Route::any('/', 'PhysiotherapyController@index')->name('physiotherapy');

    Route::get('/reset-encounter', 'PhysiotherapyController@resetEncounter')->name('physiotherapy.reset.encounter');

    Route::group(['prefix' => '/complaints', 'as' => 'physiotherapy.complaints.'], function() {
        Route::post('/insertcomplaints/', 'ComplaintController@insertComplaints')->name('save');
    });

    Route::group(['prefix' => '/history', 'as' => 'physiotherapy.history.'], function() {
        Route::post('/inserthistory/', 'HistoryController@insertHistory')->name('save');
    });

    Route::group(['prefix' => '/findings', 'as' => 'physiotherapy.findings.'], function() {
        Route::post('/insertfindings/', 'FindingController@insertFindings')->name('save');
    });

    Route::group(['prefix' => '/specialtest', 'as' => 'physiotherapy.specialtest.'], function() {
        Route::post('/insertspecialtest/', 'SpecialtestController@insertSpecialtest')->name('save');
    });

    Route::group(['prefix' => '/othermodalities', 'as' => 'physiotherapy.othermodalities.'], function() {
        Route::post('/insertothermodalities/', 'OtherModalitiesController@insertOtherModalities')->name('save');
    });

    Route::group(['prefix' => '/therapeuticexcercises', 'as' => 'physiotherapy.TherapeuticExcercises.'], function() {
        Route::post('/inserttherapeuticexcercises/', 'TherapeuticExcercisesController@insertTherapeuticExcercises')->name('save');
    });

    Route::group(['prefix' => '/advices', 'as' => 'physiotherapy.Advices.'], function() {
        Route::post('/insertadvices/', 'AdvicesController@insertAdvices')->name('save');
    });

    Route::group(['prefix' => '/nextassessment', 'as' => 'physiotherapy.nextAssessment.'], function() {
        Route::post('/insertnextassessment/', 'NextAssessmentController@insertNextAssessment')->name('save');
    });

    Route::group(['prefix' => '/diagnosis', 'as' => 'physiotherapy.diagnosis.'], function() {

        Route::post('customfreetext/save', 'DiagnosisController@saveDiagnosisCustom')->name('customfreetext.save');

        Route::post('deleteitem', 'DiagnosisController@DeleteDiagnosisItem')->name('delete');

        Route::post('/diagnosisStoreProvisional', 'DiagnosisController@diagnosisStore')->name('diagnosisStoreProvisional');

        Route::post('/finalDiagnosisStore', 'DiagnosisController@finalDiagnosisStore')->name('finalDiagnosisStore');

        Route::post('/display-obstetric-form-save-waiting', 'DiagnosisController@saveObstetricRequest')->name('obstetric.form.save.waiting');

        Route::post('/display-final-obstetric-form-save-waiting', 'DiagnosisController@saveFinalObstetricRequest')->name('final.obstetric.form.save.waiting');

    });

    Route::group(['prefix' => '/treatment', 'as' => 'physiotherapy.treatment.'], function() {

        Route::post('/insertdata', 'TreatmentController@insertTreatment')->name('save');

    });

    Route::group(['prefix' => '/essentialexamination', 'as' => 'physiotherapy.essentialexams.'], function() {

        Route::post('/insertdata', 'EssentialExaminationController@insertEssentialdata')->name('save');

    });


});
