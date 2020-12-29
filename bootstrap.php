<?php

$app = new \Mini\Foundation\Application(
    realpath(__DIR__)
);

$app->singleton(\Mini\Interfaces\Foundation\KernelContract::class, \App\Http\Kernel::class);

return $app;
