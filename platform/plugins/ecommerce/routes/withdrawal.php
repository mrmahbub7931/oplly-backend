<?php

Route::group(['namespace' => 'Canopy\Ecommerce\Http\Controllers', 'middleware' => ['web', 'core']], function () {
    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'withdrawal', 'as' => 'withdrawal.'], function () {
            Route::resource('', 'WithdrawalController')->parameters(['' => 'withdrawal']);
        });
    });
});
