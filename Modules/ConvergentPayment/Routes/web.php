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

Route::prefix('convergentpayment')->group(function() {

    Route::post('dispensingPayments',array(
        'as'=>'convergent.payments.dispensing',
        'uses'=>'ConvergentPaymentController@dispensingfonePayInit'
    ));
    Route::post('registrationPayments',array(
        'as'=>'convergent.payments.registration',
        'uses'=>'ConvergentPaymentController@registrationfonePayInit'
    ));

    Route::post('depositPayments',array(
        'as'=>'convergent.payments.deposit',
        'uses'=>'ConvergentPaymentController@depositfonePayInit'
    ));

    Route::post('dischargeClearancePayments',array(
        'as'=>'convergent.payments.dischargeClearance',
        'uses'=>'ConvergentPaymentController@dischargeClearancePaymentsfonePayInit'
    ));
    
    Route::post('depositClearancePayments',array(
        'as'=>'convergent.payments.depositClearance',
        'uses'=>'ConvergentPaymentController@depositClearancePaymentsfonePayInit'
    ));
    Route::post('creditClearancePayments',array(
        'as'=>'convergent.payments.creditClearance',
        'uses'=>'ConvergentPaymentController@creditClearancePaymentsfonePayInit'
    ));
    Route::any('payments/{encounterid}',array(
        'as'=>'convergent.payments',
        'uses'=>'ConvergentPaymentController@fonePayInit'
    ));

    Route::any('payments-process',array(
        'as'=>'convergent.payments-process',
        'uses'=>'ConvergentPaymentController@convergentPackagePaymentResponse'
    ));
    Route::any('payments-failure',array(
        'as'=>'convergent.payments-failure',
        'uses'=>'ConvergentPaymentController@convergentPackagePaymentFailure'
    ));

    
    Route::post('/save-convergent-payment-log', 'ConvergentPaymentController@saveConvergentPaymentLog')->name('billing.save.convergentpayment');
});
