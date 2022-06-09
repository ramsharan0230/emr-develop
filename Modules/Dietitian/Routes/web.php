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

Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'dietitian'], function () {
    Route::get('/', 'DietitianController@index')->name('dietitian');
    Route::post('/addDailyDietPlan', 'DietitianController@addDailyDietPlan');
    Route::post('/deleteDiet', 'DietitianController@deleteDiet');
    Route::post('/saveDailyDietPlan', 'DietitianController@saveDailyDietPlan');
    Route::get('/getTypeData', 'DietitianController@getTypeData');
    Route::get('/getDiets', 'DietitianController@getDiets');
    Route::get('/report', 'DietitianReportController@report')->name('dietitian_report');\
    Route::get('/export-dietitian-report', 'DietitianReportController@exportReport')->name('export_dietitian_report');
    Route::get('/getTypeItems', 'DietitianController@getTypeItems');

    Route::post('/saveDietitianFollowupDate', 'DietitianController@saveDietitianFollowupDate');
    Route::post('/updateExtraDosing', 'DietitianController@updateExtraDosing');
    Route::any('/dietitian-submit-bed', array(
        'as' => 'dietitian.submit.bed.form',
        'uses' => 'DietitianController@searchBed'
    ));

    Route::post('/setdietitiansessionbed', array(
        'as' => 'setdietitiansessionbed',
        'uses' => [\Modules\Bedoccupancy\Http\Controllers\BedoccupancyController::class, 'setdietitiansessionbed']
//        'uses' => 'BedoccupancyController@setdietitiansessionbed'
    ));
});
