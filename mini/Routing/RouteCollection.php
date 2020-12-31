<?php

namespace Mini\Routing;

use Mini\Foundation\Request;

class RouteCollection
{
    protected $routes = [];
    protected $allRoutes = [];

    public function add(Route $route)
    {
        $this->routes[$route->getMethod()][$route->getUri()] = $route;
        $this->allRoutes[$route->getMethod().$route->getUri()] = $route;
    }

    public function getRoutes()
    {
        return [$this->routes, $this->allRoutes];
    }

    public function match(Request $request)
    {
        $routes = $this->get($request->getMethod());
        $route = $this->matchAgainRoutes($routes, $request);
    }

    protected function matchAgainRoutes($routes, $request)
    {
        var_dump($request->getPathInfo());exit();
    }

    protected function get($method = null)
    {
        return $method ? $this->getMethodRoutes($method) : $this->allRoutes ;
    }

    protected function  getMethodRoutes($method)
    {
        return $this->routes[$method] ?? $this->allRoutes;
    }

}