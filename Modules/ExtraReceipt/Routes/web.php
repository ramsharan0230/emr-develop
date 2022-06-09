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

Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'extra-receipt', 'as'=>'extra.receipt.'], function () {
    Route::get('/', 'ExtraReceiptController@index')->name('index');
    Route::any('/get-items-by-service-or-inventory', 'ExtraReceiptController@getItemsByServiceOrInventory')->name('get.items.by.service.or.inventory');
    Route::post('/save-service', 'ExtraReceiptController@saveServiceCosting')->name('save.items.by.service');
});
