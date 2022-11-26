<?php

Route::group(['namespace' => 'Canopy\Ecommerce\Http\Controllers\Fronts', 'middleware' => ['web', 'core']], function () {
    Route::group(['prefix' => 'payment'], function () {
        Route::get('status', 'PublicCheckoutController@getPayPalStatus')->name('public.payment.paypal.status');
    });
});
