<?php

namespace Mini\Routing;

use Mini\Foundation\Application;

class Router
{
    protected $groupStack = [];

    protected $allowedMethods = [
        'get', 'post', 'put', 'patch', 'delete'
    ];

    public function __construct(protected RouteCollection $routes, protected Application $app)
    {

    }

    public function addRoute($method, $uri, $action)
    {
        $this->routes->add($this->createRoute($method, $uri, $action));
    }

    protected function createRoute($method, $uri, $action)
    {
        $route = (new Route($method, $uri, $action))->setApplication($this->app)->setRouter($this);
        if ($this->hasGroupStack()) {
            $this->mergeGroupAttributesIntoRoute($route);
        }
        return $route;
    }

    public function getRoutes()
    {
        return $this->routes->getRoutes();
    }

    protected function mergeGroupAttributesIntoRoute(Route $route)
    {
        $attributes = end($this->groupStack);
        $uri = isset($attributes['prefix']) ? trim($attributes['prefix'], '/').'/'.trim($route->getUri(), '/') : $route->getUri();
        $namespace = isset($attributes['namespace']) ? trim($attributes['namespace'], '\\') : '';
        $route->setUri($uri)->setNamespace($namespace);
    }

    protected function hasGroupStack()
    {
        return !empty($this->groupStack);
    }

    protected function mergeWithLastGroup($newAttributes)
    {
        $oldAttributes = end($this->groupStack);
        return RouteGroup::merge($newAttributes, $oldAttributes);
    }

    public function group(array $attributes, $routes)
    {
        $this->updateGroupStack($attributes);
        $this->loadRoutes($routes);
        array_pop($this->groupStack);
    }

    protected function updateGroupStack(array $attributes)
    {
        if ($this->hasGroupStack()) {
            $attributes = $this->mergeWithLastGroup($attributes);
        }

        $this->groupStack[] = $attributes;
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