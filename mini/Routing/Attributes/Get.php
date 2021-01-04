<?php

namespace Mini\Routing\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Get extends Route
{
    public function __construct(string $uri, array|string $middleware = [])
    {
        parent::__construct('get', $uri, $middleware);
    }
}