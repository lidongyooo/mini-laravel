<?php
namespace Mini;

use Psr\Container\ContainerInterface;

class Container implements ContainerInterface, \ArrayAccess
{

    protected $bindings = [];

    protected $instances = [];

    public function __construct(protected $basePath = null)
    {
        $this->bindPathsInContainer();
        $this->registerBaseBindings();
    }

    public function bind($abstract, $concrete, $share = false) :void
    {
        $this->dropStaleInstance($abstract);

        if ( !($concrete instanceof \Closure) ) {
            $concrete = function($app) use($concrete) {
                return $app->build($concrete);
            };
        }

        $this->bindings[$abstract] = compact($concrete, $share);
    }

    public function make($abstract)
    {
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        $object = $this->bindings[$abstract]['concrete']($this);

        if($this->bindings[$abstract]['share']){
            $this->instances[$abstract] = $object;
        }

        return $object;
    }

    protected function build($concrete){

        $reflector = new ReflectionClass($concrete);
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
            $dependencies[] = $this->make($parameter->getClass()->name);
        }
        return $dependencies;
    }

    public function instance($abstract, $instance)
    {
        $this->instances[$abstract] = $instance;
    }

    public function getInstances()
    {
        return $this->instances;
    }

    protected function bindPathsInContainer()
    {
        $this->instance('path.base', $this->basePath);
        $this->instance('path.app', $this->basePath.DIRECTORY_SEPARATOR.'app');
    }

    protected function registerBaseBindings()
    {
        $this->instance('app', $this);
        $this->instance(Container::class, $this);
    }

    protected function dropStaleInstance($abstract)
    {
        unset($this->instances[$abstract]);
    }

    public function get($id)
    {
        // TODO: Implement get() method.
    }

    public function has($id)
    {
        // TODO: Implement has() method.
    }

    public function offsetExists($offset)
    {
        // TODO: Implement offsetExists() method.
    }

    public function offsetGet($offset)
    {
        // TODO: Implement offsetGet() method.
    }

    public function offsetSet($offset, $value)
    {
        // TODO: Implement offsetSet() method.
    }

    public function offsetUnset($offset)
    {
        // TODO: Implement offsetUnset() method.
    }
}