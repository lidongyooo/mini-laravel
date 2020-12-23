<?php

$app = new \Mini\Foundation\Application(
    realpath(__DIR__)
);

$app->singleton(\Mini\Interfaces\Foundation\KernelContrack::class, \App\Http\Kernel::class);

return $app;
