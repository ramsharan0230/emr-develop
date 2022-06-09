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

Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'accountlist/cashier-packs'], function () {
    Route::get('/', 'CashierPacksController@index')->name('accountlist.cashier.package');
    Route::post('/accountlist-cashier-list-items', 'CashierPacksController@listItems')->name('accountlist.cashier.list.items');
    Route::post('/accountlist-cashier-list-packages', 'CashierPacksController@listPackages')->name('accountlist.cashier.list.packages');
    Route::post('/accountlist-cashier-delete-package', 'CashierPacksController@deletePackage')->name('accountlist.cashier.delete.package');
    Route::post('/accountlist-add-cashier-package', 'CashierPacksController@addPackage')->name('accountlist.add.cashier.package');
    Route::get('/exportGroup', array(
        'as'   => 'accountlist.export.group',
        'uses' => 'CashierPacksController@exportGroup'
    ));

    Route::get('/exportAll', array(
        'as'   => 'accountlist.export.all',
        'uses' => 'CashierPacksController@exportAll'
    ));
    Route::post('/accountlist-edit-item', 'CashierPacksController@editPackage')->name('accountlist.edit.item');

    Route::post('/ajax/servicecost/itemname', 'CashierPacksController@getServiceCost' )->name('ajax.servicecost.cashier.package');

});
