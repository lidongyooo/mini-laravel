<?php

namespace Mini\Routing\Attributes;

use Mini\Interfaces\Routing\Attributes\RouteAttribute;
use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Middleware implements RouteAttribute
{

    public array $middleware;

    public function __construct(string|array $middleware)
    {
        $this->middleware = is_string($middleware) ? explode(',', $middleware) : $middleware;
    }

}