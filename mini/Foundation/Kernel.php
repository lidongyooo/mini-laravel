<?php

namespace Mini\Foundation;

use Mini\Foundation\Bootstrap\HandleExceptions;
use Mini\Foundation\Bootstrap\LoadConfiguration;
use Mini\Foundation\Bootstrap\LoadEnvironmentVariables;
use Mini\Interfaces\Foundation\KernelContact;

class Kernel implements KernelContact
{

    protected $bootstrappers = [
        LoadEnvironmentVariables::class,
        LoadConfiguration::class,
        HandleExceptions::class
    ];

    public function __construct(protected Application $app)
    {

    }

    public function handle($request)
    {
        $this->app->instance('request',$request);
        $this->sendrequestThroughRouter();
    }

    public function sendRequestThroughRouter()
    {
        $this->bootstrap();
    }

    public function bootstrap()
    {
        foreach ($this->bootstrappers as $bootstrapper) {
            $this->app->make($bootstrapper)->bootstrap();
        }
    }

    public function getApplication()
    {
        // TODO: Implement getApplication() method.
    }

    public function terminate($request, $response)
    {
        // TODO: Implement terminate() method.
    }
}