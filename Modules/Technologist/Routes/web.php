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

Route::group(['middleware' =>['web', 'auth-checker'], 'prefix' => 'technologist'], function() {

    Route::get('/laboratory', 'TechnologistController@getTechnologist')->name('technologist.index');

    Route::get('/test/related-value', 'TechnologistController@getRelatedTestData');
    // insert technologist
    Route::post('/insert', 'TechnologistController@technologistInsert')->name('technologist.insert');
    Route::post('/update', 'TechnologistController@technologistUpdate')->name('technologist.update');
    Route::post('/delete', 'TechnologistController@technologistDelete')->name('technologist.delete');
    Route::get('/test/list', 'TechnologistController@technologistList');
    Route::post('/testName/update', 'TechnologistController@technologistUpdateTestName')->name('technologist.testName.update');
    Route::post('/sortByCategory', 'TechnologistController@searchByCategory')->name('technologist.sort.category');
    Route::post('/updateTestOrder', 'TechnologistController@updateTestOrder')->name('technologist.order.test');
    Route::post('/categorie/variables/updateorder', 'TechnologistController@updateOrder')->name('order.category.technologist');
    Route::post('/updatefixedcomponent', 'TechnologistController@updateTestQualiOrder')->name('order.fixed.component');
    // Category Variables
    Route::post('/category/variable/insert', 'TechnologistController@insertVariableCategory')->name('insert.category.technologist');
    Route::post('/category/variable/remove', 'TechnologistController@deleteVariableCategory')->name('delete.Category.technologist');
    Route::get('/categorie/variables/list', 'TechnologistController@getVariableCategories');
    // Sys Constant Variables
    Route::post('/constant/variable/insert', 'TechnologistController@insertVariableConstant')->name('insert.constant.technologist');
    Route::post('/constant/variable/remove', 'TechnologistController@deleteVariableConstant')->name('delete.constant.technologist');
    Route::get('/constant/variables/list', 'TechnologistController@getVariableConstants');
    // Speciman Variables
    Route::post('/specimen/variable/insert', 'TechnologistController@insertVariableSpecimen')->name('insert.specimen.technologist');
    Route::post('/specimen/variable/remove', 'TechnologistController@deleteVariableSpecimen')->name('delete.specimen.technologist');
    Route::get('/specimen/variables/list', 'TechnologistController@getVariableSpecimens');
    // Method Variables
    Route::post('/method/variable/insert', 'TechnologistController@insertVariableMethod')->name('insert.method.technologist');
    Route::post('/method/variable/remove', 'TechnologistController@deleteVariableMethod')->name('delete.method.technologist');
    Route::get('/quantitative/getMethodVariables', 'TechnologistController@getMethodVariables');

    //subtest TestOption
    Route::get('/qualitative/subtest-option', 'TechnologistController@getQualitativeSubTestOpt')->name('techno.option.subtest.get.qualitative');
    Route::post('/qualitative/insert-subtest-option', 'TechnologistController@insertQualitativeSubTestOpt')->name('insert.subtest.option');
    Route::post('/qualitative/delete-subtest-option', 'TechnologistController@deleteQualitativeSubTestOpt')->name('delete.subtest.option');

    // TestOption
    Route::get('/qualitative/test-option', 'TechnologistController@getQualitativeTestOpt')->name('techno.option.test.get.qualitative');
    Route::post('/qualitative/insert-test-option', 'TechnologistController@insertQualitativeTestOpt')->name('insert.test.option');

    Route::post('/qualitative/delete-test-option', 'TechnologistController@deleteQualitativeTestOpt')->name('delete.test.option');

    Route::post('/qualitative/insert-first-test-option', 'TechnologistController@insertFixedComponentLevelOne')->name('technologist.insert.first.level.test.option');
    Route::post('/qualitative/update-first-test-option', 'TechnologistController@updateFixedComponentLevelOne')->name('technologist.update.first.level.test.option');
    Route::post('/qualitative/fixed-sub-common-type-insert', 'TechnologistController@insertFixedComponentLevelTwo')->name('fixed.sub.common.type.insert');




    // Clinicial Scale
    Route::get('/qualitative/clinicial-scale', 'TechnologistController@getQualitativeClinicialScale')->name('techno.option.test.clinical.qualitative');
    Route::get('/qualitative/getDistinctGroup', 'TechnologistController@getDistinctGroup')->name('techno.distinct.test.get.group.qualitative');
    Route::get('/qualitative/getDistinctGroupSubTest', 'TechnologistController@getDistinctGroupSubTest')->name('techno.distinct.subtest.get.group.qualitative');
    Route::post('/qualitative/insert-clinical-scale', 'TechnologistController@insertQualitativeClinicalScale')->name('insert.clinical.scale');
    Route::post('/qualitative/delete-clinical-scale', 'TechnologistController@deleteQualitativeTestOpt')->name('delete.clinical.scale');

    // Quantitative
    Route::post('/quantitative/insert/test-para', 'TechnologistController@insertQuantitativeTestPara')->name('insert.quantitative.test.para');
    Route::post('/quantitative/update/test-para', 'TechnologistController@updateQuantitativeTestPara')->name('update.quantitative.test.para');
    Route::post('/quantitative/remove/test-para', 'TechnologistController@deleteQuantitativeTestPara')->name('delete.quantitative.test.para');
    Route::get('/quantitative/get-test-parameter-mu', 'TechnologistController@getQuantitativeTestParaMu');
    Route::get('/quantitative/get-test-parameter-si', 'TechnologistController@getQuantitativeTestParaSi');
    Route::get('/quantitative/get-test-parameter', 'TechnologistController@getQuantitativeTestPara');

    /*delete tests*/
    Route::post('/qualitative/delete/test-quali', 'TechnologistController@deleteTestQuali')->name('delete.test.quali');
    Route::post('/qualitative/delete/sub-test-quali', 'TechnologistController@deleteSubTestQuali')->name('delete.sub.test.quali');

    /*query previous data*/
    Route::get('/qualitative/old-data/test-quali', 'TechnologistController@oldTestQuali')->name('test.quali.old.data');
    Route::get('/qualitative/old-data/sub-test-quali', 'TechnologistController@oldSubTestQuali')->name('sub.test.quali.old.data');
});
