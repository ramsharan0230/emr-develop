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

Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'consultation'], function () {

    /*
     * Activity Menu User Posting
     */
    Route::post('display-userposting-form-activity-consultant', array(
        'as'   => 'display.userposting.form.activity.consultant',
        'uses' => 'UserPostingController@displayUserPostingForm'
    ));

    Route::post('add-userposting-form-activity-consultant', array(
        'as'   => 'add.userposting.form.activity.consultant',
        'uses' => 'UserPostingController@addUserPostingForm'
    ));

    Route::post('update-userposting-form-activity-consultant', array(
        'as'   => 'update.userposting.form.activity.consultant',
        'uses' => 'UserPostingController@updateUserPostingForm'
    ));
    Route::post('list-data-userposting-form-activity-consultant', array(
        'as'   => 'list.data.userposting.form.activity.consultant',
        'uses' => 'UserPostingController@listData'
    ));

    Route::post('export-data-userposting-form-activity-consultant', array(
        'as'   => 'export.data.userposting.form.activity.consultant',
        'uses' => 'UserPostingController@exporttoPdf'
    ));

    Route::post('showall-data-userposting-form-activity-consultant', array(
        'as'   => 'showall.data.userposting.form.activity.consultant',
        'uses' => 'UserPostingController@showAll'
    ));
    /*
     * End Activity Menu User Posting
     */


    /*
     * Comp Exam
     */
//    Route::post('display-compexam-form-activity-consultant', array(
//        'as'   => 'display.compexam.form.activity.consultant',
//        'uses' => 'CompExamController@displayCompExamForm'
//    ));

    Route::get('display-compexam-form-activity-consultant', array(
        'as'   => 'display.compexam.form.activity.consultant',
        'uses' => 'CompExamController@displayCompExamForm'
    ));

    Route::post('list-complaints-form-activity-consultant', array(
        'as'   => 'list.complaints.form.activity.consultant',
        'uses' => 'CompExamController@listAllComplaints'
    ));

    Route::post('display-complaint-list-activity-consultant', array(
        'as'   => 'display.complaint.list.activity.consultant',
        'uses' => 'CompExamController@displayComplaintList'
    ));

    Route::post('list-symptoms-form-activity-consultant', array(
        'as'   => 'list.symptoms.form.activity.consultant',
        'uses' => 'CompExamController@listSymptomsByCat'
    ));

    Route::post('add-symptoms-form-activity-consultant', array(
        'as'   => 'add.symptoms.form.activity.consultant',
        'uses' => 'CompExamController@addSymptoms'
    ));


    Route::post('list-examination-form-activity-consultant', array(
        'as'   => 'list.examination.form.activity.consultant',
        'uses' => 'CompExamController@listExaminationByCategory'
    ));

    Route::post('display-exmamination-list-activity-consultant', array(
        'as'   => 'display.examination.list.activity.consultant',
        'uses' => 'CompExamController@displayExaminationAddForm'
    ));

    Route::post('list-examination-bytype-form-activity-consultant', array(
        'as'   => 'list.examination.bytype.form.activity.consultant',
        'uses' => 'CompExamController@listExaminationByType'
    ));

    Route::post('add-examination-form-activity-consultant', array(
        'as'   => 'add.examination.form.activity.consultant',
        'uses' => 'CompExamController@addExamination'
    ));

    Route::post('delete-complaints-activity-consultant', array(
        'as'   => 'delete.complaints.activity.consultant',
        'uses' => 'CompExamController@deleteComplaint'
    ));

    Route::post('delete-exam-activity-consultant', array(
        'as'   => 'delete.exam.activity.consultant',
        'uses' => 'CompExamController@deleteExam'
    ));

    /*
     * End Comp Exam
     */

    /*
     * Dept Exam
     */

//    Route::post('display-deptexam-form-activity-consultant', array(
//    'as'   => 'display.deptexam.form.activity.consultant',
//    'uses' => 'DeptExamController@displayDeptExamForm'
//    ));

    Route::get('display-deptexam-form-activity-consultant', array(
        'as'   => 'display.deptexam.form.activity.consultant',
        'uses' => 'DeptExamController@displayDeptExamForm'
    ));

    Route::post('display-proc-add-form-activity-consultant', array(
    'as'   => 'display.proc.add.form.activity.consultant',
    'uses' => 'DeptExamController@displayProcAddForm'
    ));

    Route::post('list-sysconst-deptexam-activity-consultant', array(
    'as'   => 'list.sysconst.deptexam.activity.consultant',
    'uses' => 'DeptExamController@listSysConst'
    ));

    Route::post('add-variable-form-activity-consultant', array(
    'as'   => 'add.variable.form.activity.consultant',
    'uses' => 'DeptExamController@addProc'
    ));

    Route::post('delete-procname-form-activity-consultant', array(
    'as'   => 'delete.procname.form.activity.consultant',
    'uses' => 'DeptExamController@deleteProc'
    ));

    Route::post('add-deptexam-activity-consultant', array(
    'as'   => 'add.deptexam.activity.consultant',
    'uses' => 'DeptExamController@addDeptExam'
    ));

    Route::post('list-deptexam-activity-consultant', array(
    'as'   => 'list.deptexam.activity.consultant',
    'uses' => 'DeptExamController@listDeptExam'
    ));

    Route::post('edit-deptexam-activity-consultant', array(
    'as'   => 'edit.deptexam.activity.consultant',
    'uses' => 'DeptExamController@editDeptExam'
    ));

    Route::post('extract-deptexam-activity-consultant', array(
    'as'   => 'extract.deptexam.activity.consultant',
    'uses' => 'DeptExamController@extractDeptExam'
    ));

    Route::get('export-dept-exam-activity-consultant', array(
        'as'   => 'export.dept.exam.activity.consultant',
        'uses' => 'DeptExamController@exportDeptExamToPdf'
    ));

    Route::post('display-qualtiative-option-activity-consultant', array(
    'as'   => 'display.qualtiative.option.activity.consultant',
    'uses' => 'DeptExamController@displayAddOption'
    ));

    Route::post('add-options-deptexam-activity-consultant', array(
    'as'   => 'add.options.deptexam.activity.consultant',
    'uses' => 'DeptExamController@addOptions'
    ));

    Route::post('delete-options-deptexam-activity-consultant', array(
    'as'   => 'delete.options.deptexam.activity.consultant',
    'uses' => 'DeptExamController@deleteOption'
    ));


    Route::post('delete-deptexam-activity-consultant', array(
    'as'   => 'delete.deptexam.activity.consultant',
    'uses' => 'DeptExamController@deleteDeptExam'
    ));
    /*
     * End Dept Exam
     */

    /*
     * Triage Parameter
     */
    Route::group(['prefix' => 'triage-parameter'], function () {
        Route::get('/', array(
            'as'   => 'consultant.triage.parameter',
            'uses' => 'TriageParamController@triageParameter'
        ));

        Route::post('/comp-name', array(
            'as'   => 'consultant.triage.parameter.comp.detail',
            'uses' => 'TriageParamController@compDetail'
        ));

        Route::post('/complaints-list', array(
            'as'   => 'consultant.triage.parameter.complaints.list',
            'uses' => 'TriageParamController@getComplaintsList'
        ));

        Route::post('/exam-list', array(
            'as'   => 'consultant.triage.parameter.exam.list',
            'uses' => 'TriageParamController@getExamList'
        ));

        Route::post('/lab-list', array(
            'as'   => 'consultant.triage.parameter.lab.list',
            'uses' => 'TriageParamController@getLabList'
        ));

        Route::get('/complaints-add', array(
            'as'   => 'consultant.triage.parameter.complaints.add',
            'uses' => 'TriageParamController@addComplaints'
        ));

        Route::post('/complaints-param', array(
            'as'   => 'consultant.triage.parameter.complaints.param',
            'uses' => 'TriageParamController@paramComplaints'
        ));

        Route::post('/complaints-sish-add', array(
            'as'   => 'consultant.triage.parameter.complaints.sish.add',
            'uses' => 'TriageParamController@sishComplaintAdd'
        ));

        Route::post('/complaints-delete', array(
            'as'   => 'consultant.triage.parameter.complaint.delete',
            'uses' => 'TriageParamController@ComplaintDelete'
        ));

        Route::get('/exam-add', array(
            'as'   => 'consultant.triage.parameter.exams.add',
            'uses' => 'TriageParamController@addExam'
        ));

        Route::post('/exam-param', array(
            'as'   => 'consultant.triage.parameter.exam.param',
            'uses' => 'TriageParamController@paramExam'
        ));

        Route::any('/exam-sish-add', array(
            'as'   => 'consultant.triage.parameter.exams.sish.add',
            'uses' => 'TriageParamController@sishExamAdd'
        ));

        Route::post('/exam-delete', array(
            'as'   => 'consultant.triage.parameter.exam.delete',
            'uses' => 'TriageParamController@sishExamDelete'
        ));

        Route::get('/lab-add', array(
            'as'   => 'consultant.triage.parameter.labs.add',
            'uses' => 'TriageParamController@addLab'
        ));

        Route::post('/lab-param', array(
            'as'   => 'consultant.triage.parameter.lab.param',
            'uses' => 'TriageParamController@paramLab'
        ));

        Route::any('/lab-sish-add', array(
            'as'   => 'consultant.triage.parameter.labs.sish.add',
            'uses' => 'TriageParamController@sishLabAdd'
        ));

        Route::post('/lab-delete', array(
            'as'   => 'consultant.triage.parameter.lab.delete',
            'uses' => 'TriageParamController@sishLabDelete'
        ));
    });



});
