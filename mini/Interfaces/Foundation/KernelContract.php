<?php

namespace Mini\Interfaces\Foundation;

interface KernelContract
{

    public function bootstrap();

    public function handle($request);

    public function terminate($request, $response);

    public function getApplication();
}