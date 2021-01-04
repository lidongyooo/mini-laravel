<?php

namespace Mini\Routing\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Patch extends Route
{
    public function __construct(string $uri, array|string $middleware = [])
    {
        parent::__construct('patch', $uri, $middleware);
    }
}