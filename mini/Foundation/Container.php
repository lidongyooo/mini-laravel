<?php

namespace Mini\Foundation;

use Mini\Exceptions\Foundation\BindingResolutionException;

class Container
{

    protected $bindings = [];

    protected $instances = [];

    protected static $instance;

    public static function getContainer()
    {
        if (is_null(self::$instance)) {
            static::$instance = new static(realpath(__DIR__.DIRECTORY_SEPARATOR.'../'));
        }

        return static::$instance;
    }

    public function instance($abstract, $instance)
    {
        $this->instances[$abstract] = $instance;
    }

    public function getInstances()
    {
        return $this->instances;
    }

    public function singleton($abstract, $concrete)
    {
        $this->bind($abstract, $concrete, true);
    }

    public function bind($abstract, $concrete, $share = false)
    {
        $this->dropStaleInstance($abstract);

        if ( !($concrete instanceof \Closure) ) {
            $concrete = function($app) use($concrete) {
                return $app->build($concrete);
            };
        }

        $this->bindings[$abstract] = compact('concrete', 'share');
    }

    public function make($abstract)
    {
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        if($this->isBuildable($abstract)){
            $object = $this->build($abstract);
        }else {
            $object = $this->resolve($abstract);
        }

        return $object;
    }

    protected function resolve($abstract)
    {
        $object = $this->bindings[$abstract]['concrete']($this);

        if ($this->bindings[$abstract]['share']) {
            $this->instances[$abstract] = $object;
        }

        return $object;
    }

    public function isBuildable($abstract)
    {
        if (!isset($this->bindings[$abstract]) && class_exists($abstract)) {
            return true;
        }

        return false;
    }

    protected function build($concrete)
    {

        $reflector = new \ReflectionClass($concrete);
        $constructor = $reflector->getConstructor();

        if( is_null($constructor) ){
            return $reflector->newInstance();
        }else{

            $dependencies = $constructor->getParameters();
            $instances = $this->getDependencies($dependencies);
            return $reflector->newInstanceArgs($instances);
        }

    }

    protected function getDependencies($parameters){
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $dependencies[] = $this->resolveClass($parameter);
        }

        return $dependencies;
    }

    protected function resolveClass(\ReflectionParameter $parameter)
    {
        $type = $parameter->getType();

        if ($type->isBuiltin() && $type->allowsNull()) {
            return $parameter->getDefaultValue();
        }

        if ($type->isBuiltin() && !$type->allowsNull()) {
            throw new BindingResolutionException("Unresolvable dependency resolving [$parameter] in class {$parameter->getDeclaringClass()->getName()}");
        }

        return $this->make($type->getName());
    }

    protected function dropStaleInstance($abstract)
    {
        unset($this->instances[$abstract]);
    }

}