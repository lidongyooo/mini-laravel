<?php

namespace Mini\Routing\Attributes;

use Mini\Interfaces\Routing\Attributes\RouteAttribute;
use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Route implements RouteAttribute
{
    public array $middleware;

    public function __construct(public string $method, public string $uri, string|array $middleware = [])
    {
        $this->middleware = is_string($middleware) ? explode(',', $middleware) : $middleware;
    }
}