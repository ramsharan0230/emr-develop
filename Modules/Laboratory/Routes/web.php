<?php

Route::group([
    'middleware' => ['web', 'auth-checker'],
    'prefix' => 'admin/laboratory',
    'as' => 'laboratory.'
], function () {
    Route::match(['get', 'post'], 'testmethod', 'TestMethodController@index')->name('testmethod');

    Route::get('/', array(
        'as' => 'admin.laboratory',
        'uses' => 'LaboratoryController@index'
    ));


    Route::get('create', array(
        'as' => 'admin.laboratory.create',
        'uses' => 'LaboratoryController@create'
    ));

    Route::post('store', array(
        'as' => 'admin.laboratory.store',
        'uses' => 'LaboratoryController@store'
    ));

    Route::get('edit/{id}', array(
        'as' => 'admin.laboratory.edit',
        'uses' => 'LaboratoryController@edit'
    ));

    Route::post('update/{id}', array(
        'as' => 'admin.laboratory.update',
        'uses' => 'LaboratoryController@update'
    ));

    Route::get('destroy/{id}', array(
        'as' => 'admin.laboratory.destroy',
        'uses' => 'LaboratoryController@destroy'
    ));

    Route::post('addpathocategory', array(
        'as' => 'admin.laboratory.addpathocategory',
        'uses' => 'LaboratoryController@addpathocategory'
    ));

    Route::get('pathocatdestroy/{id}', array(
        'as' => 'admin.laboratory.deletepathocat',
        'uses' => 'LaboratoryController@deletepathocat'
    ));

    Route::post('addsampletype', array(
        'as' => 'admin.laboratory.addsampletype',
        'uses' => 'LaboratoryController@addsampletype'
    ));

    Route::get('sampledestroy/{id}', array(
        'as' => 'admin.laboratory.deletesample',
        'uses' => 'LaboratoryController@deletesample'
    ));

    Route::post('addsysconst', array(
        'as' => 'admin.laboratory.addsysconst',
        'uses' => 'LaboratoryController@addsysconst'
    ));

	Route::post('syncIMU', array(
		'as' => 'admin.laboratory.syncIMU',
		'uses' => 'IMUController@syncIMU'
	));

    Route::get('sysconstdestroy/{id}', array(
        'as' => 'admin.laboratory.deletesysconst',
        'uses' => 'LaboratoryController@deletesysconst'
    ));

    Route::get('test-pdf', array(
        'as' => 'view.test.pdf',
        'uses' => 'TestSamplingController@getTestPdf'
    ));

    Route::group([
        'prefix' => 'top'
    ], function () {
        Route::get('/ten-lab-test', array(
            'as' => 'top.ten.lab.test',
            'uses' => 'TopLabTestController@index'
        ));
        Route::get('/ten-lab-test-pdf', array(
            'as' => 'top.ten.lab.test.pdf',
            'uses' => 'TopLabTestController@pdfTopLabTestReport'
        ));
        Route::get('/ten-lab-test-export', array(
            'as' => 'top.ten.lab.test.export',
            'uses' => 'TopLabTestController@exportTopLabTestReport'
        ));

        Route::get('/ten-radio-test', array(
            'as' => 'top.ten.radio.test',
            'uses' => 'TopRadioTestController@index'
        ));
        Route::get('/ten-radio-test-pdf', array(
            'as' => 'top.ten.radio.test.pdf',
            'uses' => 'TopRadioTestController@pdfTopRadioTestReport'
        ));
        Route::get('/ten-radio-test-export', array(
            'as' => 'top.ten.radio.test.export',
            'uses' => 'TopRadioTestController@exportTopRadioTestReport'
        ));
    });

    Route::group([
        'prefix' => 'addition',
        'as' => 'addition.'
    ], function () {
        Route::match(['get', 'post'], '', 'TestAdditionController@index')->name('index');
        Route::get('addTest', 'TestAdditionController@addTest');
        Route::post('updateTest', 'TestAdditionController@updateTest');
        Route::post('updateSpecimen', 'TestAdditionController@updateSpecimen');
        Route::post('deleteTest', 'TestAdditionController@deleteTest');

        Route::get('getSelectOptions', 'TestAdditionController@getSelectOptions');
        Route::post('addVariable', 'TestAdditionController@addVariable');
        Route::post('deleteVariable', 'TestAdditionController@deleteVariable');
        Route::get('/laboratory-reset-encounter', 'TestAdditionController@resetEncounter')->name('reset.laboratory.encounter');

        Route::post('/laboratory-last-encounter', 'TestAdditionController@lastEncounter')->name('last.laboratory.encounter');
        Route::post('/laboratory-last-encounter-change', 'TestAdditionController@setLastEncounter')->name('change.laboratory.encounter');

        /*worksheet and barcode*/
        Route::get('worksheet', 'TestAdditionController@updateTestWorksheet');
        Route::get('barcode', 'TestAdditionController@updateTestBarcode');

        Route::post('deleteTestData', 'TestAdditionController@deleteTestData');
    });

    Route::group([
        'prefix' => 'sampling',
        'as' => 'sampling.'
    ], function () {
        Route::get('samplingPatientReport', 'TestSamplingController@samplingPatientReport')->name('samplingPatientReport');
        Route::match(['get', 'post'], '', 'TestSamplingController@index')->name('index');
        Route::get('getTest', 'TestSamplingController@getTests');
        Route::get('getPatLabTest', 'TestSamplingController@getPatLabTest');
        // Route::get('addPatLabTest', 'TestSamplingController@addPatLabTest');
        Route::post('updateTest', 'TestSamplingController@updateTest')->name('update');

        Route::get('getAutoId', 'TestSamplingController@getAutoId');

        Route::get('getTestGroupList', 'TestSamplingController@getTestGroupList');

        Route::post('saveTestGroupList', 'TestSamplingController@saveTestGroupList');
        /*worksheet and barcode*/
        Route::get('worksheet', 'TestSamplingController@updateTestWorksheet')->name('worksheet');
        Route::get('barcode', 'TestSamplingController@updateTestBarcode');
        Route::get('testingbarcodessss', function(){
            return view('laboratory::pdf.sampling-B')->render();
        });

    });

    Route::group([
        'prefix' => 'receiving',
        'as' => 'receiving.'
    ], function () {
        Route::get('', 'TestReceivingController@index')->name('index');
        Route::get('getPatientDetail', 'TestReceivingController@getPatientDetail');
        Route::post('update-test', 'TestReceivingController@updateTest')->name('update');
    });

    Route::group([
        'prefix' => 'unsampled',
        'as' => 'unsampled.'
    ], function () {
        Route::get('', 'TestUnsamplingRequestController@index')->name('index');
        Route::get('add/unsampled/patlabtest', 'TestUnsamplingRequestController@addUnsampledPatlabtest')->name('add.unsampled.patlabtest');
        Route::get('pdf/unsampled/test', 'TestUnsamplingRequestController@pdfUnsampledTest')->name('pdf.unsampled.test');
        Route::get('export/unsampled/test', 'TestUnsamplingRequestController@exportExcelUnsampledTest')->name('add.unsampled.patlabtest');
        Route::get('test-list', 'TestUnsamplingRequestController@testList')->name('test-list');
        Route::get('change/status', 'TestUnsamplingRequestController@changeUnsampledStatus')->name('unsampled.status');
    });

    Route::group([
        'prefix' => 'reporting',
        'as' => 'reporting.'
    ], function () {
        Route::get('', 'TestReportingController@index')->name('index');
        Route::get('getLabTestPatient', 'TestReportingController@getLabTestPatient');
        Route::get('getPatientDetail', 'TestReportingController@getPatientDetail');
        Route::get('sampleReport', 'TestReportingController@sampleReport');
        Route::post('changeStatus', 'TestReportingController@changeStatus');
        Route::post('changeQuantity', 'TestReportingController@changeQuantity');
        Route::post('addComment', 'TestReportingController@addComment');
        Route::post('addCondition', 'TestReportingController@addCondition');
        Route::post('displayQualitativeForm', 'TestReportingController@displayQualitativeForm');
        Route::post('displayQualitativeFormUpdate', 'TestReportingController@displayQualitativeFormUpdate');
        Route::get('load-pdf/{encounter_id}', 'TestReportingController@loadPdf');
        Route::get('history-pdf/{encounter_id}', 'TestReportingController@historyPdf');
        Route::get('all-pdf/{category_id?}', 'TestReportingController@generateAllPdfData');

        Route::post('saveQualitativeData', 'TestReportingController@saveQualitativeData');
        Route::post('save-Qualitative-Data-Update', 'TestReportingController@saveQualitativeDataUpdate');

        Route::get('getPatientImage', 'TestReportingController@getPatientImage');
        Route::post('savePatientImage', 'TestReportingController@savePatientImage');
        Route::post('updatePatientImage', 'TestReportingController@updatePatientImage');

        Route::get('getTestGraphData', 'TestReportingController@getTestGraphData');
        Route::post('addAllComment', 'TestReportingController@addAllComment');

        Route::post('updateSpecimen', 'TestReportingController@updateSpecimen');
        Route::post('updateMethod', 'TestReportingController@updateMethod');

        Route::post('deleteTest', 'TestReportingController@deleteTest');
    });

    Route::group([
        'prefix' => 'printing',
        'as' => 'printing.'
    ], function () {
        Route::match(['get', 'post'], '', 'TestPrintingController@index')->name('index');
        Route::post('searchPatient', 'TestPrintingController@searchPatient')->name('searchPatient');
        Route::any('printReport', 'TestPrintingController@printReport')->name('print.report');
        Route::post('saveReport', 'TestPrintingController@saveReport')->name('saveReport');
        Route::get('print-report-1', 'TestPrintingController@printReport1')->name('print-report-1');
    });

    Route::group([
        'prefix' => 'verify',
        'as' => 'verify.'
    ], function () {
        Route::match(['get', 'post'], '', 'TestPrintingController@index')->name('index');
        Route::post('verifyReport', 'TestPrintingController@verifyReport')->name('verifyReport');
        Route::post('comment/add', 'TestPrintingController@commentAdd')->name('comment.add');
        Route::post('change-verify-quantity', 'TestPrintingController@changeQuantity');
    });

    Route::group([
        'prefix' => 'tat',
        'as' => 'tat.'
    ], function () {
        Route::get('', 'TatController@index')->name('index');
        Route::get('report', 'TatController@report')->name('report');
        Route::get('reportexcel', 'TatController@reportexcel')->name('reportexcel');
    });

    Route::group([
        // 'prefix' => 'culture'
    ], function () {
        Route::get('getCultureComponents', 'TestReportingController@getCultureComponents');
        Route::post('saveCultureComponents', 'TestReportingController@saveCultureComponents');

        Route::get('getCultureComponentSubtests', 'TestReportingController@getCultureComponentSubtests');
        Route::post('saveCultureSubtables', 'TestReportingController@saveCultureSubtables');
        Route::post('deleteSubtables', 'TestReportingController@deleteSubtables');
        Route::post('deleteCultureComponent', 'TestReportingController@deleteCultureComponent');
    });

    Route::group([
        'prefix' => 'tracking',
        'as' => 'tracking.'
    ], function () {
        Route::get('/', 'SampleTrackingController@index')->name('index');
        Route::post('/save-in', 'SampleTrackingController@createUpdateSampleTrackIn')->name('create.update.sample.track.in');
        Route::post('/save-out', 'SampleTrackingController@createUpdateSampleTrackOut')->name('create.update.sample.track.out');
    });

    Route::group([
        'prefix' => 'bulk',
        'as' => 'bulk.'
    ], function () {
        Route::get('verify', 'TestBulkController@verify')->name('verify');
        Route::post('verify/getPatients', 'TestBulkController@getPatients')->name('getPatients');

        Route::get('print', 'TestBulkController@print')->name('print');
        Route::get('printReport', 'TestBulkController@printReport')->name('printReport');
    });

    Route::group([
        'prefix' => 'template',
        'as' => 'template.'
    ], function () {
        Route::post('/', 'TestAdditionController@getTemplateBody')->name('get.body');
    });
});

Route::get('pcr/printing/qrprint', 'TestPrintingController@qrPrint')->name('qr.print.pcr');
