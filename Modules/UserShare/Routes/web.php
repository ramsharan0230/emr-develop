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

Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'user-shares'], function () {

    Route::get('/', 'UserShareController@index')->name('usershare.index');
    Route::post('/cloneUserShare', 'UserShareController@clone')->name('usershare.clone');
    Route::post('/', 'UserShareController@store')->name('usershare.store');
    Route::get('/item-types/{billing_set}', 'UserShareController@getItemTypeFromBillingType')->name('usershare.get-item-types');
    Route::get('/doctor-billing-modes/{doctor_id}', 'UserShareController@getDoctorBillingMode')->name('usershare.get-doctor-billing-modes');
    Route::get('/doctor-item-types/{doctor_id}/{billing_id}', 'UserShareController@getDoctorItemType')->name('usershare.get-doctor-item-types');
    Route::get('/doctors-item-list/{doctor_id}/{billing_id}/{item_type_id}/{category}', 'UserShareController@getDoctorItemList')->name('usershare.get-doctor-item-list');
    Route::get('/doctors-category-list/{doctor_id}//{billing_id}/{item_type_id}', 'UserShareController@getDoctorCategoryList')->name('usershare.get-doctor-category-list');
    Route::get('/doctors/{item_name}', 'UserShareController@getDoctorListFromItemName')->name('usershare.get-doctor-list');
    Route::get('/item-list/{item_type}', 'UserShareController@getItemListFromItemType')->name('usershare.get-item-list');
    Route::get('/category/{category}/item-list/{itemType}', 'UserShareController@getItemListFromCategory')->name('usershare.category.item-list');
    Route::get('/filter', 'UserShareController@filter')->name('usershare.filter');

    Route::get('/testssf', 'UserShareController@testSSF');

    Route::post('/sub-category/store', 'UserShareController@storeSubCategory')->name('usershare.store.sub-category');

    Route::get('/ot-group-sub-categories', 'UserShareController@getAllOtGroupList')->name('usershare.ot-group-sub-categories');

    Route::get('/test-update-user-share', 'UserShareDynamicFuncitonController@update');
});
