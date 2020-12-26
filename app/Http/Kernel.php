<?php
namespace App\Http;

use Mini\Foundation\Kernel as HttpKernel;
use Mini\Middleware\First;
use Mini\Middleware\Second;

class Kernel extends HttpKernel
{

    protected $middleware = [
        First::class,
        Second::class
    ];

}