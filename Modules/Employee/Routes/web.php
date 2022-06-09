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

Route::prefix('employee')->group(function() {
    Route::get('/', 'EmployeeController@index')->name('employees');
    Route::get('/getDetails/{id}', 'EmployeeController@getEmployeeDetails')->name('employees.getDetails');
    Route::get('/store', 'EmployeeController@saveDetails')->name('employees.store');
    Route::delete('/delete/{id}', 'EmployeeController@delete')->name('employees.delete');
});

Route::prefix('behalf')->group(function() {
    Route::get('/', 'BehalfController@index')->name('behalf');
    Route::get('/getDetails/{id}', 'BehalfController@getEmployeeDetails')->name('behalf.getDetails');
    Route::post('/updateStatus/{id}', 'BehalfController@updateStatus')->name('behalf.updateStatus');
    Route::post('/updatePatta/{id}', 'BehalfController@updatePatta')->name('behalf.updatePatta');
//    Route::get('/store', 'EmployeeController@saveDetails')->name('employees.store');
//    Route::delete('/delete/{id}', 'EmployeeController@delete')->name('employees.delete');
});
