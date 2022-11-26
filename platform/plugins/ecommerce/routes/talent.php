<?php

Route::group(['namespace' => 'Canopy\Ecommerce\Http\Controllers\Talent', 'middleware' => ['web', 'core']], function () {
    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'talent', 'as' => 'talent.'], function () {
            Route::resource('', 'TalentController')->parameters(['' => 'talent']);
            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'TalentController@deletes',
                'permission' => 'talent.destroy',
            ]);

            Route::get('get-talent-order-reports-table/{id}', [
                'as'         => 'get-talent-order-reports-table',
                'uses'       => 'TalentController@getTalentOrderReportsTable',
                'permission' => 'talent.index',
            ]);
            Route::get('/sales-export/',[
                'as'    => 'sales-export',
                'uses'  => 'TalentController@salesReportExport',
                'permission' => 'talent.index',
            ]);

        });

        Route::group(['prefix' => 'talent', 'as' => 'talent.'], function () {
            Route::get('get-list-talent-for-select', [
                'as'         => 'get-list-talent-for-select',
                'uses'       => 'TalentController@getListCustomerForSelect',
                'permission' => 'talent.index',
            ]);

            Route::get('get-list-talent-for-search', [
                'as'         => 'get-list-talent-for-search',
                'uses'       => 'TalentController@getListCustomerForSearch',
                'permission' => 'talent.index',
            ]);

            Route::post('update-email/{id}', [
                'as'         => 'update-email',
                'uses'       => 'TalentController@postUpdateEmail',
                'permission' => 'talent.edit',
            ]);

            Route::get('get-customer-order-numbers/{id}', [
                'as'         => 'get-customer-order-numbers',
                'uses'       => 'TalentController@getCustomerOrderNumbers',
                'permission' => 'talent.index',
            ]);

            Route::post('create-customer-when-creating-order', [
                'as'         => 'create-customer-when-creating-order',
                'uses'       => 'TalentController@postCreateCustomerWhenCreatingOrder',
                'permission' => 'talent.create',
            ]);
        });
    });
});
Route::group(['namespace' => 'Canopy\Ecommerce\Http\Controllers', 'middleware' => ['web', 'core']], function () {
    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {
        Route::resource('withdrawal', 'WithdrawalController')->parameters(['' => 'withdrawal']);
    });
});
Route::group(['namespace' => 'Canopy\Ecommerce\Http\Controllers', 'middleware' => ['web', 'core']], function () {
    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {
        Route::resource('region', 'RegionController')->parameters(['' => 'region']);
    });
});
Route::group(['namespace' => 'Canopy\Ecommerce\Http\Controllers\Talent', 'middleware' => ['web', 'core']], function () {
    Route::group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function () {
        Route::get('ajax/get-availability', [
            'as'   => 'public.get-availability',
            'uses' => 'PublicController@getBookingAvailabilitySlots',
        ]);
        Route::post('ajax/notify-when-back', [
            'as'   => 'public.notify-when-back.post',
            'uses' => 'PublicController@postNotifyWhenBack',
        ]);
    });
});

Route::group([
    'namespace'  => 'Canopy\Ecommerce\Http\Controllers\Talent',
    'middleware' => ['web', 'core'],
    'prefix'     => 'talent',
    'as'         => 'talent.',
], function () {

    Route::get('signup', [
        'as'   => 'signup',
        'uses' => 'PublicController@getSignup',
    ]);

    Route::post('signup', [
        'as'   => 'signup.post',
        'uses' => 'PublicController@postSignup',
    ]);
});
Route::group([
    'namespace'  => 'Canopy\Ecommerce\Http\Controllers\Talent',
    'middleware' => ['api'],
    'prefix'     => 'talent',
    'as'         => 'talent.',
], function () {

    Route::post('requests/upload/{id}', [
        'as'   => 'requests.upload-video',
        'uses' => 'PublicController@postUploadRequestVideo',
    ]);

    Route::get('requests/download/{id}', [
        'as'   => 'requests.download-video',
        'uses' => 'PublicController@getDownloadVideo',
    ]);
});

Route::group([
    'namespace'  => 'Canopy\Ecommerce\Http\Controllers\Talent',
    'middleware' => ['web', 'core', 'customer'],
    'prefix'     => 'talent',
    'as'         => 'talent.',
], function () {

    Route::get('overview', [
        'as'   => 'overview',
        'uses' => 'PublicController@getOverview',
    ]);

    Route::get('edit-account', [
        'as'   => 'edit-account',
        'uses' => 'PublicController@getEditAccount',
    ]);

    Route::post('edit-account', [
        'as'   => 'edit-account.post',
        'uses' => 'PublicController@postEditTalentAccount',
    ]);

    Route::get('bookings', [
        'as'   => 'bookings',
        'uses' => 'PublicController@getViewBookings',
    ]);
    Route::get('change-availability', [
        'as'   => 'change-availability',
        'uses' => 'PublicController@getChangeAvailability',
    ]);

    Route::post('change-availability', [
        'as'   => 'change-availability.post',
        'uses' => 'PublicController@postChangeAvailability',
    ]);

    Route::get('edit-bank-details', [
        'as'   => 'edit-bank-details',
        'uses' => 'PublicController@getEditBankDetails',
    ]);

    Route::post('edit-bank-details', [
        'as'   => 'edit-bank-details.post',
        'uses' => 'PublicController@postEditBankDetails',
    ]);

    Route::get('transaction-history', [
        'as'   => 'transaction-history',
        'uses' => 'PublicController@getTransactionHistory',
    ]);

    Route::get('requests', [
        'as'   => 'requests',
        'uses' => 'PublicController@getListRequests',
    ]);

    Route::get('requests/view/{id}', [
        'as'   => 'requests.view',
        'uses' => 'PublicController@getViewRequest',
    ]);

    Route::post('requests/update-video/{id}', [
        'as'   => 'requests.update-video',
        'uses' => 'PublicController@postUpdateRequest',
    ]);



    Route::get('request/accept/{id}', [
        'as'   => 'requests.accept',
        'uses' => 'PublicController@getAcceptRequest',
    ]);

    Route::get('request/reject/{id}', [
        'as'   => 'requests.reject',
        'uses' => 'PublicController@getRejectRequest',
    ]);

    Route::get('request/release/{id}', [
        'as'   => 'requests.release',
        'uses' => 'PublicController@getReleaseRequest',
    ]);

    Route::get('orders', [
        'as'   => 'orders',
        'uses' => 'PublicController@getListOrders',
    ]);

    Route::get('orders/view/{id}', [
        'as'   => 'orders.view',
        'uses' => 'PublicController@getViewOrder',
    ]);

    Route::get('order/cancel/{id}', [
        'as'   => 'orders.cancel',
        'uses' => 'PublicController@getCancelOder',
    ]);

    Route::get('orders/print/{id}', [
        'as'   => 'print-order',
        'uses' => 'PublicController@getPrintOrder',
    ]);

    Route::post('avatar', [
        'as'   => 'avatar',
        'uses' => 'PublicController@postAvatar',
    ]);
});
