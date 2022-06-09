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

Route::group(['middleware' =>['web', 'auth-checker'], 'prefix' => 'variables', 'as' => 'variables.'], function() {

    Route::group(['prefix' => 'bodyfluids', 'as' => 'bodyfluid.'], function() {
        Route::get('/', 'VariablesController@bodyfluid')->name('index');
        Route::post('/add', 'VariablesController@addBodyfluids')->name('add');
        Route::delete('/delete/{fldid}', 'VariablesController@deleteBodyfluids')->name('delete');
    });

    Route::group(['prefix' => 'ethnicgroups', 'as' => 'ethnicgroup.'], function() {
        Route::get('/', 'VariablesController@ethnicGroup')->name('index');
        Route::post('/getsurnames', 'VariablesController@getSurnameFromGroupName')->name('getsurname');
        Route::post('/surnamefilter', 'VariablesController@surnameFilter')->name('surnamefilter');
        Route::post('/addsurname', 'VariablesController@addSurname')->name('addsurname');
        Route::delete('/deleteethnicgroup/{fldid}', 'VariablesController@deleteEthnicgroup')->name('delete');
    });

});
