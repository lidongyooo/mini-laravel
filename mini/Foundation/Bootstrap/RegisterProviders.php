<?php

namespace Mini\Foundation\Bootstrap;

use Mini\Foundation\Application;

class RegisterProviders
{
    public function bootstrap(Application $app)
    {
        $app->registerServiceProviders($app->make('config')->get('app.providers'));
    }
}