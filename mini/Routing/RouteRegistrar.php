<?php

namespace Mini\Routing;

class RouteRegistrar
{
    protected $allowedAttributes = [
       'middleware', 'namespace', 'prefix'
    ];

    protected $passthru = [
        'get', 'post', 'put', 'patch', 'delete'
    ];

    protected $attributes = [];

    public function __construct(protected Router $router)
    {
    }

    public function attribute($key, $value)
    {
        $this->attributes[$key] = $value;
        return $this;
    }

    protected function registerRoute(...$arguments)
    {
        return $this->router->addRoute(...$arguments);
    }

    public function group($routes)
    {
        $this->router->group($this->attributes, $routes);
    }

    public function __call($method, $arguments)
    {
        if (in_array($method, $this->passthru)) {
            return $this->registerRoute($method, ...$arguments);
        }

        if (in_array($method, $this->allowedAttributes)) {
            return $this->attribute($method, $arguments[0]);
        }

        throw new \BadMethodCallException('Call to undefined method '. $this::class .'::'.$method);
    }
}