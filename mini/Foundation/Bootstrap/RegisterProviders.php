<?php

namespace Mini\Foundation\Bootstrap;

use Mini\Foundation\Application;

class RegisterProviders
{
    public function bootstrap(Application $app)
    {
        $app->registerServiceProviders(config('app.providers'));
    }
}