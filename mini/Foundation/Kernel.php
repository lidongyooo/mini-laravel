<?php

namespace Mini\Foundation;

use Mini\Foundation\Bootstrap\HandleExceptions;
use Mini\Foundation\Bootstrap\LoadConfiguration;
use Mini\Foundation\Bootstrap\LoadEnvironmentVariables;
use Mini\Foundation\Bootstrap\RegisterFacades;
use Mini\Foundation\Bootstrap\RegisterProviders;
use Mini\Interfaces\Foundation\KernelContrack;

class Kernel implements KernelContrack
{

    protected $bootstrappers = [
        LoadEnvironmentVariables::class,
        LoadConfiguration::class,
        HandleExceptions::class,
        RegisterFacades::class,
        RegisterProviders::class
    ];

    public function __construct(protected Application $app)
    {

    }

    public function handle($request)
    {
        $this->app->instance('request',$request);
        $this->sendRequestThroughRouter();
    }

    public function sendRequestThroughRouter()
    {
        $this->bootstrap();
    }

    public function bootstrap()
    {
        foreach ($this->bootstrappers as $bootstrapper) {
            $this->app->make($bootstrapper)->bootstrap($this->app);
        }
    }

    public function getApplication()
    {
        return $this->app;
    }

    public function terminate($request, $response)
    {
        // TODO: Implement terminate() method.
    }
}