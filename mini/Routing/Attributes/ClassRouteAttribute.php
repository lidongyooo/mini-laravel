<?php

namespace Mini\Routing\Attributes;

class ClassRouteAttribute
{
    public function __construct(protected \ReflectionClass $class)
    {
    }

    public function prefix()
    {
        if ($attributeClass = $this->getAttribute(Prefix::class)) {
            return $attributeClass->prefix;
        }

        return null;
    }

    public function middleware()
    {
        if ($attributeClass = $this->getAttribute(Middleware::class)) {
            return $attributeClass->middleware;
        }

        return [];
    }

    protected function getAttribute(string $attributeClass)
    {
        $attributes = $this->class->getAttributes($attributeClass);

        return count($attributes) ? $attributes[0]->newInstance() : null;
    }
}