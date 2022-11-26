<?php

Route::group([
    'middleware' => ['web', 'api'],
    'prefix'     => 'api/v1',
    'namespace'  => 'Canopy\Ecommerce\Http\Controllers\API',
], function () {
    Route::post('login', 'LoginController@login');
    Route::post('register', 'CustomerController@store');
    Route::post('talent-register', 'TalentController@store');

    Route::get('search/{keyword}', 'TalentController@search')->name('api.search');
    Route::get('search/recommended', 'TalentController@search');

    Route::get('requests', 'TalentRequestController@index');
    Route::get('requests/{id}', 'TalentRequestController@get');
    Route::post('requests/{id}/accept', 'TalentRequestController@acceptRequest')->middleware('api-customer');
    Route::post('requests/{id}/reject', 'TalentRequestController@rejectRequest')->middleware('api-customer');
    Route::post('requests/{id}/release', 'TalentRequestController@releaseRequest')->middleware('api-customer');
    Route::post('requests/{id}/cancel', 'TalentRequestController@cancelRequest')->middleware('api-customer');
    Route::post('requests/create', 'TalentRequestController@store');
    Route::post('requests/{id}', 'TalentRequestController@update')->middleware('api-customer');

    Route::get('talents', 'TalentController@index');
    // Route::post('talents', 'TalentController@store');
    // Route::put('talents/{id}', 'TalentController@update');
    Route::get('talents/{id}', 'TalentController@get');
    // Route::get('talents/{id}/videos', 'TalentController@getPublicVideos');
    // Route::get('talents/{id}/requests', 'TalentController@getOrderHistory');
    Route::get('talents/{id}/history', 'TalentController@getOrderHistory');
    // Route::get('talents/{id}/availability', 'TalentController@getBookingAvailability');
    // Route::post('talents/{id}/availability', 'TalentController@updateBookingAvailability');
    Route::post('talents/notify-when-back', 'TalentController@postNotifyWhenBack')->name('api.notify-when-back');
    // Route::get('general/booking-slots', 'TalentController@getBookingAvailabilitySlots');

    Route::get('customers/{id}', 'CustomerController@get');
    Route::post('customers/{id}', 'CustomerController@update');
    Route::get('customers/{id}/favourites', 'CustomerController@getFavouritesList');
    Route::post('customers/{id}/favourites', 'CustomerController@getFavouritesList');
    // Route::get('customers/{id}/requests', 'CustomerController@getOrderHistory');
    Route::get('customers/{id}/history', 'CustomerController@getOrderHistory');
    Route::get('customers/{id}/favourites', 'CustomerController@getOrderHistory');

    Route::get('categories', 'CategoryController@index');
    // Route::get('categories/{id}', 'CategoryController@get');

    Route::get('occasions', 'OccasionController@index');
    Route::get('occasions/{id}', 'OccasionController@get');

    // Route::get('orders/{id}', 'CheckoutController@getCheckout');
    // Route::post('orders/{id}', 'CheckoutController@postCheckout');
    Route::get('orders/{token}', 'CheckoutController@getCheckout');
    Route::post('orders/checkout', 'CheckoutController@createCheckout');
    Route::post('orders/{token}/process', 'CheckoutController@processCheckout');

    Route::get('media', 'MediaController@get');
    Route::post('media', 'MediaController@store');

    Route::post('me', 'CustomerController@update')->middleware('api-customer');
    Route::get('me/favourites', 'CustomerController@getFavourites')->middleware('api-customer');
    Route::get('me/stats', 'TalentController@getStats')->middleware('api-customer');
    Route::post('me/password', 'CustomerController@updatePassword')->middleware('api-customer');
    Route::post('me/notifications', 'CustomerController@updateNotifications')->middleware('api-customer');
    Route::post('me/talent', 'TalentController@update')->middleware('api-customer');
    Route::post('me/talent/banking', 'TalentController@updateBanking')->middleware('api-customer');
});

Route::group([
    'middleware' => 'api',
    'prefix'     => 'oauth',
    'namespace'  => 'Canopy\Ecommerce\Http\Controllers\API',
], function () {
    Route::get('me', 'CustomerController@getUser')->middleware('api-customer');
    Route::post('me', 'CustomerController@update')->middleware('api-customer');
    Route::post('me/talent', 'TalentController@update')->middleware('api-customer');
    Route::post('login/social', 'SocialLoginController@login');
    Route::post('logout', 'CustomerController@logout');
});
