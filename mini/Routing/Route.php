<?php

namespace Mini\Routing;

use Mini\Foundation\Application;

class Route
{
    protected $app;

    protected $router;

    protected $namespace = '';

    public function __construct(protected $method, protected $uri, protected $action)
    {
        $this->parseAction($this->action);
    }

    protected function parseAction($action)
    {
        if ($action instanceof \Closure) {
            return $action;
        }

        if (is_string($action)) {
            $this->action = explode('@', $action);
        }

        if (is_array($action)) {
            $action = $action[0].'@'.$action[1];
        }

        $this->action['controller'] = $action;
        $this->action['uses'] = $action;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getActionName()
    {
        return $this->action['controller'] ?? 'Closure';
    }

    public function getActionMethod()
    {
        return end(explode('@', $this->getActionName()));
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function setUri($uri)
    {
        $this->uri = $uri;
        return $this;
    }

    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;

        return $this;
    }

    public function run()
    {
        // do something
    }

    public function setRouter(Router $router)
    {
        $this->router = $router;
        return $this;
    }

    public function setApplication(Application $app)
    {
        $this->app = $app;
        return $this;
    }

}