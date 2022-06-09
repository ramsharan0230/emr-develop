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

Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'dental'], function () {
   Route::match(['get', 'post'], '/', 'DentalController@index')->name('dental');
  Route::post('/dentalexamgeneral', 'DentalController@dentalExamgeneral')->name('dental.examgeneral');
  Route::get('/dental-reset-encounter', 'DentalController@resetDentalEncounter')->name('dental.reset.encounter');
  Route::post('/dental/dynamic-form', array(
        'as'   => 'dental.dynamic.form',
        'uses' => 'DentalController@displayDynamicView'
    ));
  Route::get('/dental-opdsheet/{id}', 'DentalController@generateOpdSheet')->name('dental.opdsheet.generate');
  Route::get('/dental-history/{id}', 'DentalController@dentalHistoryPdf')->name('dental.history.generate');

  Route::post('/teethData', 'DentalController@teethData')->name('dental.teethData');
});
