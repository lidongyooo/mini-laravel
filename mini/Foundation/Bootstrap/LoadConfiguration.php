<?php

namespace Mini\Foundation\Bootstrap;

use Mini\Foundation\Application;

class LoadConfiguration
{
    protected $items = [];

    public function __construct(protected Application $app)
    {
    }

    public function bootstrap()
    {
        $this->app->instance('config', $this);
        $this->loadConfigurationFiles();

        date_default_timezone_set($this->get('app.timezone', 'Asia/Shanghai'));
        mb_internal_encoding('UTF-8');
    }

    public function get($key, $default = null)
    {
        $array = $this->items;

        if ($this->exists($this->items, $key)){
            return $this->items[$key];
        }

        if (!str_contains($key, '.')) {
            return $default;
        }

        foreach (explode('.', $key) as $segment) {
            if ($this->exists($array, $segment)) {
                $array = $array[$segment];
            } else {
                return $default;
            }
        }

        return $array;
    }

    public function set($key, $value = null)
    {
        $array = &$this->items;
        $keys = explode('.', $key);

        while (count($keys) > 1) {
            $key = array_shift($keys);

            if ( !$this->exists($this->items, $key) ) {
                $array[$key] = [];
            }

            $array = &$array[$key];
        }

        $array[array_shift($keys)] = $value;
    }

    public function exists($array, $key)
    {
        return isset($array[$key]);
    }

    protected function loadConfigurationFiles()
    {
        $directory = $this->app->make('path.config');

        $folders = new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new \RecursiveIteratorIterator($folders);
        foreach ($files as $file) {
            if ($file->getExtension() === 'php') {
                $this->transformNormalValue($directory, $file);
            }
        }
    }

    protected function transformNormalValue($directory, \SplFileInfo $file)
    {
        $alias = $this->getAlias($directory, $file);
        $array = require_once $file->getRealPath();

        $this->set($alias, $array);
    }

    protected function getAlias($directory, \SplFileInfo $file)
    {
        $basename = str_replace('.php', '', str_replace($directory.DIRECTORY_SEPARATOR, '', $file->getRealPath()));
        return str_replace(DIRECTORY_SEPARATOR, '.', $basename);
    }
}