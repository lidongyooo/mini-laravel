<?php
namespace Mini;

use Psr\Container\ContainerInterface;

class Container
{

    protected $bindings = [];

    protected $instances = [];

    protected static $instance;

    public function __construct(protected $basePath = null)
    {
        $this->bindPathsInContainer();
        $this->registerBaseBindings();
    }

    public function bind($abstract, $concrete, $share = false)
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

    public function instance($abstract, $instance)
    {
        $this->instances[$abstract] = $instance;
    }

    public function getInstances()
    {
        return $this->instances;
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self(realpath(__DIR__.'/../'));
        }

        return self::$instance;
    }

    protected function getDependencies($parameters){
        $dependencies = [];
        foreach ($parameters as $parameter) {
            $dependencies[] = $this->make($parameter->getClass()->name);
        }
        return $dependencies;
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
        $this->instance(Container::class, $this);
    }

    protected function dropStaleInstance($abstract)
    {
        unset($this->instances[$abstract]);
    }

}