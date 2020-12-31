<?php

namespace Mini\Routing;

use Mini\Foundation\Application;
use Mini\Foundation\Request;

class Router
{
    protected $groupStack = [];

    protected $allowedMethods = [
        'get', 'post', 'put', 'patch', 'delete'
    ];

    protected $currentRequest;

    protected $currentRoute;

    public function __construct(protected RouteCollection $routes, protected Application $app)
    {

    }

    public function addRoute($method, $uri, $action)
    {
        $method = strtoupper($method);
        $this->routes->add($this->createRoute($method, $uri, $action));
    }

    protected function createRoute($method, $uri, $action)
    {
        $route = (new Route($method, $uri, $action))->setApplication($this->app)->setRouter($this);
        if ($this->hasGroupStack()) {
            $this->mergeGroupAttributesIntoRoute($route);
        }

        $route->setUri($uri[0] === '/' ? substr($uri, 1) : $uri);

        return $route;
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

    public function dispatch(Request $request)
    {
        $this->currentRequest = $request;
        return $this->dispatchToRoute($request);
    }

    protected function dispatchToRoute(Request $request)
    {
        return $this->runRoute($request, $this->findRoute($request));
    }

    protected function runRoute(Request $request, Route $route)
    {
        return $route->run();
    }

    protected function findRoute(Request $request)
    {
        $this->currentRoute = $route = $this->routes->match($request);

        $this->app->instance(Route::class, $route);

        return $route;
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