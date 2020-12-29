<?php

namespace Mini\Foundation;

use Mini\Foundation\Bootstrap\BootProviders;
use Mini\Foundation\Bootstrap\HandleExceptions;
use Mini\Foundation\Bootstrap\LoadConfiguration;
use Mini\Foundation\Bootstrap\LoadEnvironmentVariables;
use Mini\Foundation\Bootstrap\RegisterFacades;
use Mini\Foundation\Bootstrap\RegisterProviders;
use Mini\Interfaces\Foundation\KernelContrack;
use Mini\Routing\Router;

class Kernel implements KernelContrack
{

    protected $middleware = [];

    protected $bootstrappers = [
        LoadEnvironmentVariables::class,
        LoadConfiguration::class,
        HandleExceptions::class,
        RegisterFacades::class,
        RegisterProviders::class,
        BootProviders::class
    ];

    public function __construct(protected Application $app, protected Router $router)
    {

    }

    public function handle($request)
    {
        return $this->sendRequestThroughRouter($request);
    }

    public function sendRequestThroughRouter($request)
    {
        $this->app->instance('request',$request);

        $this->bootstrap();

        return (new Pipeline($this->app))
                    ->send($request)
                    ->through($this->middleware)
                    ->then($this->dispatchToRouter());

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

    }

    protected function dispatchToRouter()
    {
        return function ($request) {
            return 'I did it';
        };
    }
}