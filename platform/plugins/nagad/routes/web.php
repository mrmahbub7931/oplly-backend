<?php

Route::group(['namespace' => 'Canopy\Nagad\Http\Controllers', 'middleware' => ['web', 'core']], function () {
    Route::get('nagad/payment/callback', [
        'as'   => 'nagad.payment.callback',
        'uses' => 'NagadController@paymentCallback',
    ]);
});
