<?php

Route::group(['namespace' => 'Canopy\Mollie\Http\Controllers', 'middleware' => ['core']], function () {
    Route::post('mollie/payment/callback', [
        'as'   => 'mollie.payment.callback',
        'uses' => 'MollieController@paymentCallback',
    ]);
});
