<?php

namespace Mini\Foundation\Bootstrap;

use Mini\Foundation\Application;

class BootProviders
{

    public function bootstrap(Application $app)
    {
        $app->boot();
    }

}