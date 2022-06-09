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

Route::group([
    'middleware' => ['web', 'auth-checker'],
    'prefix' => 'bloodbank',
    'as' => 'bloodbank.',
], function() {
    Route::group([
        'prefix' => 'question-master',
        'as' => 'question-master.',
    ], function() {
        Route::match(['get', 'post'], '', 'QuestionMasterController@index')->name('index');
        Route::post('changeStatus', 'QuestionMasterController@changeStatus')->name('changeStatus');
    });

    Route::group([
        'prefix' => 'bag-master',
        'as' => 'bag-master.',
    ], function() {
        Route::match(['get', 'post'], '', 'BagMasterController@index')->name('index');
    });

    Route::group([
        'prefix' => 'donor-master',
        'as' => 'donor-master.',
    ], function() {
        Route::match(['get', 'post'], '', 'DonorMasterController@index')->name('index');
        Route::get('searchPatient', 'DonorMasterController@searchPatient')->name('searchPatient');
    });


    Route::group([
        'prefix' => 'consent-form',
        'as' => 'consent-form.',
    ], function() {
        Route::match(['get', 'post'], '', 'ConsentFormController@index')->name('index');
        Route::get('searchPatient', 'ConsentFormController@searchPatient')->name('searchPatient');
        Route::post('store', 'ConsentFormController@store')->name('store');
    });

    Route::group([
        'prefix' => 'blood-bag',
        'as' => 'blood-bag.',
    ], function() {
        Route::match(['get', 'post'], '', 'BloodBagGenerationController@index')->name('index');
        Route::get('searchPatient', 'BloodBagGenerationController@searchPatient')->name('searchPatient');
    });


    Route::group([
        'prefix' => 'test-result',
        'as' => 'test-result.',
    ], function() {
        Route::match(['get', 'post'], '', 'TestresultController@index')->name('index');
        Route::get('search', 'TestresultController@search')->name('search');
    });

    Route::group([
        'prefix' => 'component-separation',
        'as' => 'component-separation.',
    ], function() {
        Route::match(['get', 'post'], '', 'ComponentSeperationController@index')->name('index');
        Route::get('search', 'ComponentSeperationController@searchPatient')->name('searchPatient');
    });

    Route::group([
        'prefix' => 'result-authorization',
        'as' => 'result-authorization.',
    ], function() {
        Route::match(['get', 'post'], '', 'ResultauthorizationController@index')->name('index');
        Route::get('search', 'ResultauthorizationController@search')->name('search');
    });

    Route::group([
        'prefix' => 'test-pending',
        'as' => 'test-pending.',
    ], function() {
        Route::match(['get', 'post'], '', 'TestpendingController@index')->name('index');
        Route::get('search', 'TestpendingController@search')->name('search');
    });

    Route::group([
        'prefix' => 'cross-matching-result',
        'as' => 'cross-matching-result.',
    ], function() {
        Route::match(['get', 'post'], '', 'CrossmatchingresultController@index')->name('index');
        Route::get('search', 'CrossmatchingresultController@search')->name('search');
    });

});
