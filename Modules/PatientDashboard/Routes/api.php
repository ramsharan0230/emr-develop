<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//->middleware('patient-auth-checker');
Route::get('/patient', 'PatientDashboardApiController@test');
Route::post('/patient/login', 'PatientDashboardApiController@login');


Route::middleware('patient-api-auth-token')->group(function () {
    // Logout
    Route::post('/logout','PatientDashboardApiController@patientLogout');
    Route::get('/patient/profile', 'PatientDashboardApiController@profile');    
    Route::get('/patient/lab-report-and-history', 'PatientDashboardApiController@labReportAndHistory');    
  });

