<?php

Route::prefix("admin")
    ->group(function () {
        Route::get('dashboard', 'DashboardController@index');
        Route::prefix("order")
            ->group(function () {
                Route::post('add', 'OrderController@add');
                Route::post('index', 'OrderController/index');
            });
    });