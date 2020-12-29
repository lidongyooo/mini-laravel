<?php

namespace Mini\Foundation;

use Mini\Interfaces\Foundation\ServiceProviderContract;

class ServiceProvider implements ServiceProviderContract
{
    public $bindings = [];
    public $singletons = [];

    public function __construct(protected Application $app)
    {

    }

    public function register()
    {

    }

    public function boot()
    {

    }

}