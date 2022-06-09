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
Route::group(['middleware' =>['web', 'auth-checker'], 'prefix' => 'diagnosis'], function() {

//    generic routes for adding and deleting categories from laboratory, examinations and radiology

    Route::post('/addpathocategory', 'DiagnosisController@addPathoCategory')->name('addpathocategory');
    Route::post('/addsysconstant', 'DiagnosisController@addSysConstant')->name('addsysconstant');
    Route::post('/addspecimen', 'DiagnosisController@addSpecimen')->name('addspecimen');
    Route::delete('/deletecategory/{fldid}', 'DiagnosisController@deleteCategory')->name('deletepathocategory');
    Route::delete('/deletesysconstant/{fldsysconst}', 'DiagnosisController@deleteSysconstant')->name('deletesysconstant');
    Route::delete('/deletespecimen/{fldid}', 'DiagnosisController@deleteSpecimen')->name('deletespecimen');

//    options related routes

    // Method Variables
    Route::post('/method/variable/insert', 'DiagnosisController@insertVariableMethod')->name('insert.method.technologist');
    Route::post('/method/variable/remove', 'DiagnosisController@deleteVariableMethod')->name('delete.method.technologist');
    Route::get('/quantitative/getMethodVariables', 'DiagnosisController@getMethodVariables');

    // Quantitative
    Route::post('/quantitative/insert/test-para', 'DiagnosisController@insertQuantitativeTestPara')->name('insert.quantitative.test.para');
    Route::post('/quantitative/update/test-para', 'DiagnosisController@updateQuantitativeTestPara')->name('update.quantitative.test.para');
    Route::post('/quantitative/remove/test-para', 'DiagnosisController@deleteQuantitativeTestPara')->name('delete.quantitative.test.para');
    Route::get('/quantitative/get-test-parameter-mu', 'DiagnosisController@getQuantitativeTestParaMu');
    Route::get('/quantitative/get-test-parameter-si', 'DiagnosisController@getQuantitativeTestParaSi');
    Route::get('/quantitative/get-test-parameter', 'DiagnosisController@getQuantitativeTestPara');

    // TestOption
    Route::get('/qualitative/test-option', 'DiagnosisController@getQualitativeTestOpt')->name('techno.option.test.get.qualitative');


    Route::post('/qualitative/insert-first-test-option', 'DiagnosisController@insertFixedComponentLevelOne')->name('insert.first.level.test.option');
//    Route::post('/qualitative/fixed-sub-common-type-insert', 'DiagnosisController@insertFixedComponentLevelTwo')->name('fixed.sub.common.type.insert');

    // Clinicial Scale
    Route::get('/qualitative/clinicial-scale', 'DiagnosisController@getQualitativeClinicialScale')->name('techno.option.test.clinical.qualitative');
    Route::get('/qualitative/getDistinctGroup', 'DiagnosisController@getDistinctGroup')->name('techno.distinct.test.get.group.qualitative');
    Route::post('/qualitative/insert-clinical-scale', 'DiagnosisController@insertQualitativeClinicalScale')->name('insert.clinical.scale');
    Route::post('/qualitative/delete-clinical-scale', 'DiagnosisController@deleteQualitativeTestOpt')->name('delete.clinical.scale');

//    options related routes end

//    Examination routes

    Route::prefix('examination')->as('examination.')->group(function() {
        Route::get('/', 'ExaminationController@index')->name('examinationlist');
        Route::post('/addexamination', 'ExaminationController@addExamination')->name('add');
        Route::get('/editexamination/{fldexamid}', 'ExaminationController@editExamination')->name('editexamination');
        Route::patch('/updateexamination/{fldexamid}', 'ExaminationController@updateExamination')->name('updateexamination');
        Route::get('/deleteexamination/{fldexamid}', 'ExaminationController@deleteExamination')->name('deleteexamination');
        Route::post('/searchoption', 'ExaminationController@searchExamination')->name('examination.listing.search');

//subtest TestOption
        Route::get('/qualitative/subtest-option', 'ExaminationController@getQualitativeSubTestOpt')->name('techno.option.subexam.get.qualitative');
        Route::post('/qualitative/insert-subexam-option', 'ExaminationController@insertQualitativeSubTestOpt')->name('insert.subexam.option');
        Route::post('/qualitative/delete-subexam-option', 'ExaminationController@deleteQualitativeSubTestOpt')->name('delete.subexam.option');
        Route::post('/qualitative/insert-test-option', 'ExaminationController@insertQualitativeTestOpt')->name('insert.test.option');
        Route::post('/qualitative/delete-test-option', 'ExaminationController@deleteQualitativeTestOpt')->name('delete.test.option');
        Route::get('/qualitative/test-option', 'ExaminationController@getQualitativeTestOpt')->name('option.test.get.qualitative');

        Route::post('/qualitative/insert-first-test-option', 'ExaminationController@insertFixedComponentLevelOne')->name('insert.first.level.test.option');

        // TestOption
        Route::get('/qualitative/exam-option', 'ExaminationController@getQualitativeTestOpt')->name('techno.option.exam.get.qualitative');
        Route::post('/qualitative/insert-exam-option', 'ExaminationController@insertQualitativeTestOpt')->name('insert.exam.option');

        Route::post('/qualitative/delete-exam-option', 'ExaminationController@deleteQualitativeTestOpt')->name('delete.exam.option');

        Route::post('/qualitative/insert-first-exam-option', 'ExaminationController@insertFixedComponentLevelOne')->name('insert.first.level.exam.option');
        Route::post('/qualitative/fixed-sub-common-type-insert', 'ExaminationController@insertFixedComponentLevelTwo')->name('fixed.sub.common.type.insert');

        // Clinicial Scale
        Route::get('/qualitative/clinicial-scale', 'ExaminationController@getQualitativeClinicialScale')->name('techno.option.exam.clinical.qualitative');
        Route::get('/qualitative/getDistinctGroup', 'ExaminationController@getDistinctGroup')->name('techno.distinct.exam.get.group.qualitative');
        Route::post('/qualitative/insert-clinical-scale', 'ExaminationController@insertQualitativeClinicalScale')->name('insert.clinical.scale');
        Route::post('/qualitative/delete-clinical-scale', 'ExaminationController@deleteQualitativeTestOpt')->name('delete.clinical.scale');
        Route::get('/qualitative/clinicial-scale', 'ExaminationController@getQualitativeClinicialScale')->name('option.test.clinical.qualitative');
        Route::get('/qualitative/getDistinctGroup', 'ExaminationController@getDistinctGroup')->name('distinct.test.get.group.qualitative');

        // Quantitative
        Route::post('/quantitative/insert/exam-para', 'ExaminationController@insertQuantitativeTestPara')->name('insert.quantitative.exam.para');
        Route::post('/quantitative/update/exam-para', 'ExaminationController@updateQuantitativeTestPara')->name('update.quantitative.exam.para');
        Route::post('/quantitative/remove/exam-para', 'ExaminationController@deleteQuantitativeTestPara')->name('delete.quantitative.exam.para');
        Route::get('/quantitative/get-exam-parameter-mu', 'ExaminationController@getQuantitativeTestParaMu');
        Route::get('/quantitative/get-exam-parameter-si', 'ExaminationController@getQuantitativeTestParaSi');
        Route::get('/quantitative/get-exam-parameter', 'ExaminationController@getQuantitativeTestPara');

        /*delete exams*/
        Route::post('/qualitative/delete/exam-quali', 'ExaminationController@deleteTestQuali')->name('delete.exam.quali');
        Route::post('/qualitative/delete/sub-exam-quali', 'ExaminationController@deleteSubTestQuali')->name('delete.sub.exam.quali');

        /*query previous data*/
        Route::get('/qualitative/old-data/exam-quali', 'ExaminationController@oldTestQuali')->name('exam.quali.old.data');
        Route::get('/qualitative/old-data/sub-test-quali', 'ExaminationController@oldSubTestQuali')->name('sub.test.quali.old.data');
    });

//    laboratory routes

    Route::group(['prefix' => 'laboratory', 'as' => 'diagnostictest.'], function() {
        Route::get('/', 'LaboratoryController@index')->name('list');
        Route::post('/addtest', 'LaboratoryController@addTest')->name('add');
        Route::get('/edittest/{fldtestid}', 'LaboratoryController@editTest')->name('edit');
        Route::patch('/updatetest/{fldtestid}', 'LaboratoryController@updateTest')->name('update');
        Route::delete('/deletetest/{fldtestid}', 'LaboratoryController@deleteTest')->name('delete');
    });

//   radiology routes
    Route::group(['prefix' => 'radiology', 'as' => 'radiodiagnostic.'], function() {
        Route::get('/', 'RadiologyController@index')->name('list');
        Route::post('/addradio', 'RadiologyController@addRadio')->name('add');
        Route::get('/editradio', 'RadiologyController@editRadio')->name('edit');
        Route::post('/updateradio', 'RadiologyController@updateRadio')->name('update');
        Route::get('/deleteradio', 'RadiologyController@deleteRadio')->name('delete');
        Route::post('/searchoption', 'RadiologyController@searchRadiology')->name('listing.search');
        Route::post('/insertTextAddition', 'RadiologyController@insertTextAddition')->name('insert.text-addition');
        Route::post('/getTextAddition', 'RadiologyController@getTextAddition')->name('get.text-addition');
        Route::get('/deleteTextAddition', 'RadiologyController@deleteTextAddition')->name('delete.text-addition');
        Route::post('/examName/update', 'RadiologyController@radiologyUpdateTestName')->name('examName.update');
    });
});
