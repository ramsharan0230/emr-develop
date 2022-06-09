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
    'prefix' => 'consultant-list',
    'as' => 'consultantlist.'
], function () {
    Route::get('/', array(
        'as' => 'home',
        'uses' => 'ConsultantListController@index'
    ));
    Route::post('/search-consultant-data', array(
        'as' => 'search.data',
        'uses' => 'ConsultantListController@searchData'
    ));

    Route::post('/search-consultant-by-encounter', array(
        'as' => 'search.data.by.encounter',
        'uses' => 'ConsultantListController@searchDataByEncounter'
    ));

    Route::post('/search-consultant-by-name', array(
        'as' => 'search.data.by.name',
        'uses' => 'ConsultantListController@searchDataByName'
    ));

    Route::post('/search-encounter-data', array(
        'as' => 'encounter.data',
        'uses' => 'ConsultantListController@encounterDataForAddition'
    ));

    Route::post('/consultant-create', array(
        'as' => 'consultant.create',
        'uses' => 'ConsultantListController@createConsultant'
    ));

    Route::post('/consultant-edit', array(
        'as' => 'consultant.edit',
        'uses' => 'ConsultantListController@editFormConsultantList'
    ));

    Route::post('/consultant-update', array(
        'as' => 'consultant.list.update',
        'uses' => 'ConsultantListController@updateConsultant'
    ));

    Route::post('/consultant-add-follow-up-date', array(
        'as' => 'consultant.follow.up.date.add',
        'uses' => 'ConsultantListController@followUpDateAdd'
    ));
});
