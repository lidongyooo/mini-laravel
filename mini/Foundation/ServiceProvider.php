<?php

namespace Mini\Foundation;

use Mini\Interfaces\Foundation\ServiceProviderContact;

class ServiceProvider implements ServiceProviderContact
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