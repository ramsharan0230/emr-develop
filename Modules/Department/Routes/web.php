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
Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'department/'], function () {
    Route::any('/', 'DepartmentController@index')->name('department');
    Route::post('/deletebed', 'DepartmentController@deletebed')->name('deletebed');
    Route::post('/add-departement', 'DepartmentController@adddepartement')->name('add-departement');
    Route::post('/bed-search', 'DepartmentController@getbedbydept')->name('bed-search');
    Route::post('/category-search', 'DepartmentController@categorysearch')->name('category-search');
    Route::post('/addbed', 'DepartmentController@addbed')->name('addbed');
    Route::get('/editbed', 'DepartmentController@editbed')->name('editbed');
    Route::post('/updatebed', 'DepartmentController@updatebed')->name('updatebed');
    Route::post('/update-department', 'DepartmentController@updatedepartment')->name('update-department');
    Route::post('/delete-department', 'DepartmentController@deletedepartment')->name('delete-departement');
    Route::get('/exportdepartment', 'DepartmentController@exportdepartment')->name('exportdepartment');
    Route::get('/avg-revenue', 'DepartmentController@getAvgRevenuePerDept')->name('getAvgRevenuePerDept');
    Route::get('/avg-revenue-detail/{dept_id}', 'DepartmentController@getAvgRevenuePerDeptDetail')->name('getAvgRevenuePerDeptDetail');
    Route::get('/avg-revenue-per-person', 'DepartmentController@getAvgRevenuePerPerson')->name('getAvgRevenuePerPerson');
    Route::get('/avg-revenue-per-person-detail/{encounter_id}', 'DepartmentController@getAvgRevenuePerPersonDetail')->name('getAvgRevenuePerPersonDetail');


    Route::get('/autobilling', 'AutobillingController@index')->name('autobilling');
    Route::post('/getItemname', 'AutobillingController@getItemname')->name('getItemname');
    Route::post('/saveAutobilling', 'AutobillingController@saveAutobilling')->name('saveAutobilling');
    Route::post('/updateEnabledeptAutobilling', 'AutobillingController@updateEnabledeptAutobilling')->name('updateEnabledeptAutobilling');
    Route::post('/checkEnableCheckbox', 'AutobillingController@checkEnableCheckbox')->name('checkEnableCheckbox');
    Route::post('/listAllAutobilling', 'AutobillingController@listAllAutobilling')->name('listAllAutobilling');
    Route::post('/deleteAutobilling', 'AutobillingController@deleteAutobilling')->name('deleteAutobilling');
    Route::post('/editAutobilling', 'AutobillingController@editAutobilling')->name('editAutobilling');
    Route::post('/updateAutobilling', 'AutobillingController@updateAutobilling')->name('updateAutobilling');

    Route::get('/doctor/autobilling', 'AutobillingDoctorController@index')->name('autobillingDoctor');
    Route::post('/doctor/getItemname', 'AutobillingDoctorController@getItemname')->name('getItemname');
    Route::post('/doctor/saveAutobilling', 'AutobillingDoctorController@saveAutobilling')->name('saveAutobillingDoctor');
    Route::post('/doctor/listAllAutobilling', 'AutobillingDoctorController@listAllAutobilling')->name('listAllAutobillingDoctor');
    Route::post('/doctor/deleteAutobilling', 'AutobillingDoctorController@deleteAutobilling')->name('deleteAutobillingDoctor');
    Route::post('/doctor/editAutobilling', 'AutobillingDoctorController@editAutobilling')->name('editAutobillingDoctor');
    Route::post('/doctor/updateAutobilling', 'AutobillingDoctorController@updateAutobilling')->name('updateAutobillingDoctor');

    Route::post('/exportdepartment', 'DepartmentController@exportdepartment')->name('exportdepartment');

    Route::get('/category/{category}/departments', 'DepartmentController@getDepartmentByCategory')->name('category.departments');

    Route::get('/topTen', 'TopTenDepartmentController@index')->name('toptendepartment');
    Route::get('/topTen/report', 'TopTenDepartmentController@pdfTopTenDept')->name('pdfTopTenDept');
    Route::get('/topTen/export', 'TopTenDepartmentController@exportTopTenDept')->name('exportTopTenDept');

    Route::get('/topTenDoc', 'TopTenDoctorController@index')->name('toptendoctor');
    Route::get('/topTenDoc/report', 'TopTenDoctorController@pdfTopTenDoc')->name('pdfTopTenDoc');
    Route::get('/topTenDoc/export', 'TopTenDoctorController@exportTopTenDoc')->name('exportTopTenDoc');
});
