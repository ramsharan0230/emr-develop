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

Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'medicine-stock'], function () {
    Route::any('/', 'MedicineStockController@index')->name('medicine.stock');
    Route::any('/list-stock', 'MedicineStockController@displayStock')->name('medicine.stock.ajax.list');
    Route::any('/list-sales-stock', 'MedicineStockController@salesDataBatch')->name('medicine.stock.sales.batch');
    Route::any('/list-purchase-stock', 'MedicineStockController@purchaseDataBatch')->name('medicine.stock.purchase.batch');
});
