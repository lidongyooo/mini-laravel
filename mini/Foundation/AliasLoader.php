<?php

namespace Mini\Foundation;

class AliasLoader
{
    protected static $instance;

    private function __construct(protected array $aliases)
    {
    }

    public static function getInstance(array $aliases)
    {
        if (is_null(static::$instance)) {
            static::$instance = new static($aliases);
        }

        return static::$instance;
    }

    public function register()
    {
        spl_autoload_register([$this, 'load'], true, true);
    }

    public function load($alias)
    {
        if (isset($this->aliases[$alias])) {
            return class_alias($this->aliases[$alias], $alias);
        }
    }
}