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
    'prefix' => 'queue-management',
    'as' => 'queue.new.'
], function () {
    Route::any('/consultants', 'QueueManagementController@consults')->name('consultants');

    Route::get('/pharmacy', 'QueueManagementController@pharmacy')->name('pharmacy');

    Route::any('/pharmacy/get-data-pharmacy', 'QueueManagementController@getDynamicDataPharmacy')->name('pharmacy.get.data');

    Route::get('/laboratory', 'QueueManagementController@laboratory')->name('laboratory');

    Route::any('/laboratory/get-data-lab', 'QueueManagementController@getDynamicDataLaboratory')->name('laboratory.get.data');

    Route::get('/radiology', 'QueueManagementController@radiology')->name('radiology');
});
