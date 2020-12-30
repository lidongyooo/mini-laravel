<?php

namespace Mini\Facades;

abstract class Facade
{
    protected static $app;

    protected static $resolvedInstance = [];

    public static function setFacadeApplication($app)
    {
        static::$app = $app;
    }

    protected abstract static function getFacadeAccessor();

    public static function getFacadeRoot()
    {
        return static::resolveFacadeInstance(static::getFacadeAccessor());
    }

    protected static function resolveFacadeInstance($name)
    {
        if (isset(static::$resolvedInstance[$name])) {
            return static::$resolvedInstance[$name];
        }

        return static::$resolvedInstance[$name] = static::$app->make($name);
    }

    public static function __callStatic($method, $arguments)
    {
        $instance = static::getFacadeRoot();
        return $instance->$method(...$arguments);
    }
}