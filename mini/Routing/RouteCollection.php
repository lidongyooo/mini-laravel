<?php

namespace Mini\Routing;

use Mini\Exceptions\Routing\NotFoundHttpException;
use Mini\Foundation\Request;

class RouteCollection
{
    protected $routes = [];
    protected $allRoutes = [];

    public function add(Route $route)
    {
        $this->routes[$route->getMethod()][$route->getMethod().$route->getUri()] = $route;
        $this->allRoutes[$route->getMethod().$route->getUri()] = $route;
    }

    public function match(Request $request)
    {
        $routes = $this->get($request->getMethod());

        $route = $routes[$request->getMethod().$request->getPathinfo()] ?? null;

        return $route ?: throw new NotFoundHttpException('Not match to route');
    }

    protected function get($method = null)
    {
        return $method ? $this->getMethodRoutes($method) : $this->allRoutes ;
    }

    protected function getMethodRoutes($method)
    {
        return $this->routes[$method] ?? $this->allRoutes;
    }

}