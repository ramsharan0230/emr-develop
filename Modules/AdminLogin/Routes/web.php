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

Route::prefix('login')->group(function () {
    Route::get('/', array(
        'as' => 'cogent.login.form',
        'uses' => 'AdminLoginController@index'
    ));

    Route::post('submit', array(
        'as' => 'admin.user.profile.login',
        'uses' => 'AdminLoginController@submit'
    ));

    Route::post('submit-2fa', array(
        'as' => 'admin.user.profile.login.first.2fa',
        'uses' => 'AdminLoginController@firstLogin2fa'
    ));


});

Route::get('cogent/logout', array(
    'as' => 'admin.logout',
    'uses' => 'AdminLoginController@logOut'
));

Route::get('cogent/request-access', array(
    'as' => 'admin.request.access',
    'uses' => 'AdminLoginController@requestAccess'
));

Route::post('cogent/store-access', array(
    'as' => 'admin.request.access.store',
    'uses' => 'AdminLoginController@storeRequest'
));

/*forgot password */
Route::get('forgot-password', array(
    'as' => 'password.request',
    'uses' => 'AdminLoginController@forgotPassword'
));

Route::get('password-reset-success', array(
    'as' => 'password.reset.success',
    'uses' => 'AdminLoginController@successReset'
));

Route::post('forgot-password-submit', array(
    'as' => 'password.request.submit',
    'uses' => 'AdminLoginController@submitForgotPassword'
));

Route::get('password/reset', array(
    'as' => 'password.reset',
    'uses' => 'AdminLoginController@showResetForm'
));

Route::post('password/update', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'updatePasword'])->name('password.update');
