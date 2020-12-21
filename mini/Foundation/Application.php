<?php

namespace Mini\Foundation;

class Application extends Container
{

    public function __construct(protected $basePath = null)
    {
        $this->bindPathsInContainer();
        $this->registerBaseBindings();
    }

    protected function bindPathsInContainer()
    {
        $this->instance('path.base', $this->basePath);
        $this->instance('path.app', $this->basePath.DIRECTORY_SEPARATOR.'app');
    }

    protected function registerBaseBindings()
    {
        self::$instance = $this;
        $this->instance('app', $this);
        $this->instance(Application::class, $this);
    }
}