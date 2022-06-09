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

/*
 * Group
 */
Route::group(['prefix' => 'consultation/group'], function () {
    Route::get('/', array(
        'as'   => 'consultant.group.exam.group',
        'uses' => 'ExamGroupController@index'
    ));

    Route::post('/exam-list', array(
        'as'   => 'consultant.exam.group.list',
        'uses' => 'ExamGroupController@refreshExamList'
    ));

    Route::post('/exam-add', array(
        'as'   => 'consultant.exam.group.add',
        'uses' => 'ExamGroupController@refreshExamAdd'
    ));

    Route::post('/exam-delete', array(
        'as'   => 'consultant.exam.group.delete',
        'uses' => 'ExamGroupController@refreshExamDelete'
    ));

    Route::get('/exam-export', array(
        'as'   => 'consultant.exam.group.export',
        'uses' => 'ExamGroupController@exportExamGroup'
    ));


    /*
     * Selection List
     */
    Route::get('/selection-list', array(
        'as'   => 'consultant.group.selection.list',
        'uses' => 'SelectionListController@index'
    ));

    Route::post('/selection-populate-list', array(
        'as'   => 'consultant.selection.group.populate.list',
        'uses' => 'SelectionListController@getGroupName'
    ));

    Route::post('/selection-display-list', array(
        'as'   => 'consultant.selection.group.display.list',
        'uses' => 'SelectionListController@displayTableList'
    ));

    Route::post('/selection-add', array(
        'as'   => 'consultant.selection.group.add',
        'uses' => 'SelectionListController@addSelection'
    ));

    Route::post('/selection-delete', array(
        'as'   => 'consultant.selection.group.delete',
        'uses' => 'SelectionListController@deleteSelection'
    ));

    Route::post('/selection-group-proc', array(
        'as'   => 'display.consultation.group.proc',
        'uses' => 'ProcGroupController@displayForm'
    ));

    Route::get('/selection-group-export', array(
        'as'   => 'display.consultation.group.selection.export',
        'uses' => 'SelectionListController@selectionExport'
    ));

    /*
     * Proc Group
     */
    /*
     * DietGroup
    */
    Route::get('/diet-package', array(
        'as'   => 'display.consultation.package.diet',
        'uses' => 'DietPackageController@displayForm'
    ));

    Route::post('/addDietPackage', array(
        'as'   => 'add.diet.package.consultant',
        'uses' => 'DietPackageController@addDietPackage'
    ));

    Route::post('/displayDietItemType', array(
        'as'   => 'display.diet.item.tyoe.consultant',
        'uses' => 'DietPackageController@displayDietItemType'
    ));
    Route::post('/listDietByGroup', array(
        'as'   => 'list.group.diet.package.consultant',
        'uses' => 'DietPackageController@listDietByGroup'
    ));
    
    Route::post('/deleteDietGroupPackage', array(
        'as'   => 'delete.diet.group.package.consultant',
        'uses' => 'DietPackageController@deleteDietGroupPackage'
    ));
    /*
     * End DietGroup
    */

//    Route::post('/procGroup', array(
//        'as'   => 'display.procgroup.form.group.consultant',
//        'uses' => 'ProcGroupController@displayForm'
//    ));

    Route::get('/procGroup', array(
        'as'   => 'display.procgroup.form.group.consultant',
        'uses' => 'ProcGroupController@displayForm'
    ));

    Route::post('/addProcGroup', array(
        'as'   => 'add.group.proc.groups.consultant',
        'uses' => 'ProcGroupController@addProcGroup'
    ));

    Route::post('/deleteProcGroup', array(
        'as'   => 'delete.procgroup.groups.consultant',
        'uses' => 'ProcGroupController@deleteProcGroup'
    ));

    Route::post('/listByGroup', array(
        'as'   => 'list.by.group.groups.consultant',
        'uses' => 'ProcGroupController@listByGroupName'
    ));

    Route::get('/exportToPdfGroup', array(
        'as'   => 'export.proc.group.groups.consultant',
        'uses' => 'ProcGroupController@exportGroupToPdf'
    ));

});
