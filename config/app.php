<?php
return [
    'test' => 'value',
    'aliases' => [
        'DB' => \Mini\Facades\DB::class,
        'Route' => \Mini\Facades\Route::class
    ],
    'providers' => [
        \Mini\Database\DB::class,
        \App\Providers\RouteServiceProvider::class,
        \Mini\Routing\Attributes\RouteAttributesServiceProvider::class
    ]
];