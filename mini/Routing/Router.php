<?php

namespace Mini\Routing;

class Router
{
    protected $groupStack = [];

    protected $allowedMethods = [
        'get', 'post', 'put', 'patch', 'delete'
    ];

    public function addRoute($method, $uri, $action)
    {

    }

    public function group($attributes, $routes)
    {
        $this->loadRoutes($routes);
    }

    protected function loadRoutes($routes)
    {
        if ($routes instanceof \Closure) {
            $routes($this);
        } else {
            $router = $this;

            require_once  $routes;
        }
    }

    public function __call($method, $arguments)
    {
        if (in_array($method, $this->allowedMethods)) {
            return $this->addRoute($method, ...$arguments);
        }

        return (new RouteRegistrar($this))->$method(...$arguments);
    }

}