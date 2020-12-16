<?php

namespace Mini\Foundation;

use Mini\Interfaces\Foundation\KernelContact;

class Kernel implements KernelContact
{

    public function __construct(protected Container $app)
    {

    }

    public function bootstrap()
    {
        // TODO: Implement bootstrap() method.
    }

    public function getApplication()
    {
        // TODO: Implement getApplication() method.
    }

    public function handle($request)
    {
//        unset($request);
    }

    public function terminate($request, $response)
    {
        // TODO: Implement terminate() method.
    }
}