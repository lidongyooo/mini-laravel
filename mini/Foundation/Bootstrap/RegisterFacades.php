<?php

namespace Mini\Foundation\Bootstrap;

use Mini\Facades\Facade;
use Mini\Foundation\AliasLoader;
use Mini\Foundation\Application;

class RegisterFacades
{
    public function bootstrap(Application $app)
    {
        Facade::setFacadeApplication($app);
        AliasLoader::getInstance($app->make('config')->get('app.aliases'))->register();
    }
}