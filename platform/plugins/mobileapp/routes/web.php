<?php

Route::group(['namespace' => 'Canopy\MobileApp\Http\Controllers', 'middleware' => ['web', 'core']], function () {
    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {

        Route::get('app/settings', [
            'as'   => 'mobileapp.settings',
            'uses' => 'MobileAppController@getSettings',
        ]);

        Route::post('app/settings', [
            'as'         => 'mobileapp.settings.post',
            'uses'       => 'MobileAppController@postSettings',
            'permission' => 'mobileapp.settings',
        ]);
    });
});
