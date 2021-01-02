<?php

Route::middleware('auth')->group(function () {

        Route::get('test', [\App\Http\Controllers\TestController::class, 'output']);

        Route::prefix("order")->group(function () {
                Route::post('add', 'OrderController@add');
            });

});

Route::get('/', function(){
    return 'hello world';
});
