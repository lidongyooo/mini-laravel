<?php

namespace Mini\Routing\Attributes;

use Mini\Interfaces\Routing\Attributes\RouteAttribute;
use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Prefix implements RouteAttribute
{

    public function __construct(public string $prefix)
    {
    }

}