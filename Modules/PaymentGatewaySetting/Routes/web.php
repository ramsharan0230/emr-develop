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

Route::prefix('payment-gateway-setting')->group(function() {
    //Esewa Settings
    Route::get('list',array(
        'as'=>'admin.paymentgateway.list',
        'uses'=>'PaymentGatewaySettingController@allPayments'
    ));

    //Esewa Settings
    /*Route::get('esewa',array(
        'as'=>'admin.paymentgateway.esewa',
        'uses'=>'PaymentGatewaySettingController@esewa'
    ));

    Route::post('esewa/store',array(
        'as'=>'admin.paymentgateway.esewa.store',
        'uses'=>'PaymentGatewaySettingController@esewaStore'
    ));*/

    //Paypal Settings
    /*Route::get('paypal',array(
        'as'=>'admin.paymentgateway.paypal',
        'uses'=>'PaymentGatewaySettingController@paypal'
    ));

    Route::post('paypal/store',array(
        'as'=>'admin.paymentgateway.paypal.store',
        'uses'=>'PaymentGatewaySettingController@paypalStore'
    ));*/

    //Convergent Settings
    Route::get('convergent',array(
        'as'=>'admin.paymentgateway.convergent',
        'uses'=>'PaymentGatewaySettingController@convergent'
    ));

    Route::post('convergent/store',array(
        'as'=>'admin.paymentgateway.convergent.store',
        'uses'=>'PaymentGatewaySettingController@convergentStore'
    ));

    //IME Pay Settings
    Route::get('imepay',array(
        'as'=>'admin.paymentgateway.imepay',
        'uses'=>'PaymentGatewaySettingController@imepay'
    ));

    Route::post('imepay/store',array(
        'as'=>'admin.paymentgateway.imepay.store',
        'uses'=>'PaymentGatewaySettingController@imepayStore'
    ));

    // Nabil Credit Card
    /*Route::get('nabil-credit',array(
        'as'=>'admin.paymentgateway.nabil-credit',
        'uses'=>'PaymentGatewaySettingController@nabilCredit'
    ));

    Route::post('nabil-credit/store',array(
        'as'=>'admin.paymentgateway.nabil-credit.store',
        'uses'=>'PaymentGatewaySettingController@nabilCreditStore'
    ));*/

    // nPay Settings
    /*Route::get('npay',array(
        'as'=>'admin.paymentgateway.npay',
        'uses'=>'PaymentGatewaySettingController@nPay'
    ));

    Route::post('npay/store',array(
        'as'=>'admin.paymentgateway.npay.store',
        'uses'=>'PaymentGatewaySettingController@nPayStore'
    ));*/

    // bank charge update
    /*Route::post('bank-charge',array(
        'as'=>'admin.paymentgateway.bank-charge',
        'uses'=>'PaymentGatewaySettingController@bankCharge'
    ));*/

    // NIBL Settings
    /*Route::get('nibl',array(
        'as'=>'admin.paymentgateway.nibl',
        'uses'=>'PaymentGatewaySettingController@nibl'
    ));

    Route::post('nibl/store',array(
        'as'=>'admin.paymentgateway.nibl.store',
        'uses'=>'PaymentGatewaySettingController@niblStore'
    ));*/

    // Prabhu Settings
    /* Route::get('prabhu',array(
         'as'=>'admin.paymentgateway.prabhu',
         'uses'=>'PaymentGatewaySettingController@prabhu'
     ));

     Route::post('prabhu/store',array(
         'as'=>'admin.paymentgateway.prabhu.store',
         'uses'=>'PaymentGatewaySettingController@prabhuStore'
     ));*/

    // HBL Settings
    Route::get('hbl',array(
        'as'=>'admin.paymentgateway.hbl',
        'uses'=>'PaymentGatewaySettingController@hbl'
    ));

    Route::post('hbl/store',array(
        'as'=>'admin.paymentgateway.hbl.store',
        'uses'=>'PaymentGatewaySettingController@hblStore'
    ));

});
