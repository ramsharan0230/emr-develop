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

Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'billing-group/tax'], function () {
    Route::get('/', 'TaxGroupController@index')->name('billing.tax.group');
    Route::post('/store-tax-group', 'TaxGroupController@taxGroupStore')->name('store.tax.group');
    Route::post('/delete-tax-group', 'TaxGroupController@taxGroupDelete')->name('delete.tax.group');
});

Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'billing-group/bank'], function () {
    Route::get('/', 'BankListController@index')->name('billing.bank.group');
    Route::post('/store-bank', 'BankListController@bankStore')->name('store.bank');
    Route::post('/delete-bank', 'BankListController@bankDelete')->name('delete.bank');
    Route::post('/search-bank', 'BankListController@searchBank')->name('search.bank');
});
