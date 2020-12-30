<?php

namespace Mini\Routing;

class RouteCollection
{
    protected $routes = [];
    protected $allRoutes = [];

    public function add(Route $route)
    {
        $this->routes[$route->getMethod()][$route->getUri()] = $route;
        $this->allRoutes[$route->getUri()] = $route;
    }

    public function getRoutes()
    {
        return [$this->routes, $this->allRoutes];
    }
}