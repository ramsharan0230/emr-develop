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

Route::prefix('template')->group(function() {
    Route::get('/', 'TemplateController@index')->name('template');
    Route::post('/update', 'TemplateController@update')->name('template.update');
});