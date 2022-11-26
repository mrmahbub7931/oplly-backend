<?php

Route::group(['namespace' => 'Canopy\ShurjoPay\Http\Controllers', 'middleware' => ['web', 'core']], function () {
    Route::get('shurjopay/payment/callback', [
        'as'   => 'shurjopay.payment.callback',
        'uses' => 'ShurjoPayController@getPaymentStatus',
    ]);
});
