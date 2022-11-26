<?php

Route::group(['namespace' => 'Canopy\Bkash\Http\Controllers', 'middleware' => ['web', 'core']], function () {
    Route::get('bkash/payment/callback', [
        'as'   => 'bkash.payment.callback',
        'uses' => 'BkashController@paymentCallback',
    ]);
});
