<?php

Route::group(['middleware' => ['web']], function () {
    /**
     * Public routes
     */
    Route::get('/', 'PageController@index')->name('welcome.view');
    Route::post('/request-access', 'Auth\AuthController@requestAccess')->name('request-access');
    Route::get('/login', 'Auth\AuthController@redirectToGoogle')->name('login');
    Route::get('/login/google/callback', 'Auth\AuthController@googleCallback');
    Route::get('/logout', 'Auth\AuthController@logout')->name('logout');

    /**
     * Authenticated user routes
     */
    Route::group(['middleware' => ['auth']], function () {
        /**
         * User profile routes
         */
        Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
            Route::get('/', 'ProfileController@view')->name('view');

            Route::post('/', 'ProfileController@update')->name('update');
            Route::post('/robinhood', 'ProfileController@updateRobinhoodCredentials')->name('update.robinhood');
        });

        /**
         * Trading bot routes
         */
        Route::group(['prefix' => 'bots', 'as' => 'bots.'], function () {
            Route::get('/', 'TradeBotController@view')->name('view');
            Route::get('/form', 'TradeBotController@form')->name('form');

            Route::post('/create', 'TradeBotController@store')->name('store');
            Route::get('/{bot}/delete', 'TradeBotController@delete')->name('delete');
            Route::post('/{bot}/update', 'TradeBotController@update')->name('update');
            Route::get('/{bot}/form', 'TradeBotController@form')->name('form');
            Route::get('/{bot}/balance-ticks', 'TradeBotController@getBalanceTicks');
        });
    });
});
