<?php

namespace Mini\Foundation;

use Mini\Interfaces\Foundation\ServiceProviderContrack;

class ServiceProvider implements ServiceProviderContrack
{
    public $bindings = [];
    public $singletons = [];

    public function __construct(protected Application $app)
    {

    }

    public function register()
    {

    }
}