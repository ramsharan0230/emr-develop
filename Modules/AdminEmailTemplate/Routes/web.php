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

Route::prefix('admin/email-template')->group(function() {
    Route::get('/',array(
        'as'=>'admin.emailtemplate',
        'uses'=>'AdminEmailTemplateController@index'
    ));

    Route::get('edit/{Id}',array(
        'as'=>'admin.emailtemplate.edit',
        'uses'=>'AdminEmailTemplateController@edit'
    ));

    Route::post('update',array(
        'as'=>'admin.emailtemplate.update',
        'uses'=>'AdminEmailTemplateController@update'
    ));

    Route::get('email',array(
        'as'=>'admin.emailtemplate.email',
        'uses'=>'AdminEmailTemplateController@sendEmail'
    ));
});
