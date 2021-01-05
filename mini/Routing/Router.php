<?php

namespace Mini\Routing;

use Mini\Foundation\Application;
use Mini\Foundation\Pipeline;
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
        return $this->routes->add($this->createRoute($method, $uri, $action));
    }

    protected function createRoute($method, $uri, $action)
    {
        $route = (new Route($method, $uri, $action))->setApplication($this->app)->setRouter($this);
        if ($this->hasGroupStack()) {
            $this->mergeGroupAttributesIntoRoute($route);
        }

        return $route;
    }

    protected function mergeGroupAttributesIntoRoute(Route $route)
    {
        $attributes = end($this->groupStack);

        $prefix = isset($attributes['prefix']) ? trim($attributes['prefix'], '/') : '';

        $namespace = isset($attributes['namespace']) ? trim($attributes['namespace'], '\\') : '';

        $route->prefix($prefix)->namespace($namespace)->middleware($attributes['middleware'] ?? []);
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

    public function syncMiddleware($middleware)
    {
        $this->middleware = $middleware;
    }

    protected function dispatchToRoute(Request $request)
    {
        return $this->runRoute($request, $this->findRoute($request));
    }

    protected function runRoute(Request $request, Route $route)
    {
        return $this->runRouteWithinStack($request, $route);
    }

    protected function runRouteWithinStack(Request $request, Route $route)
    {
        return (new Pipeline($this->app))
                    ->send($request)
                    ->through($this->gatherRouteMiddleware($route))
                    ->then(function ($request) use ($route) {
                        return $route->run();
                    });
    }

    protected function gatherRouteMiddleware(Route $route)
    {
        $middleware = [];
        foreach ($route->getMiddleware() as $key) {
            $middleware[] = $this->middlewareExists($this->middleware[$key] ?? $key);
        }
        return $middleware;
    }

    protected function middlewareExists($middleware)
    {
        return class_exists($middleware) && (in_array($middleware, $this->middleware) || isset($this->middleware[$middleware])) ? $middleware : throw new \RuntimeException("The middleware::$middleware was not found");;
    }

    protected function findRoute(Request $request)
    {
        $this->currentRoute = $route = $this->routes->match($request);

        $this->app->instance('route', $route);

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