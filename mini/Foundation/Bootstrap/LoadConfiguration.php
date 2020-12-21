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
    }

    public function get()
    {

    }

    public function set($key, $value = null)
    {
        $array = &$this->items;
        $keys = explode('.', $key);

        while (count($keys) > 1) {
            $key = array_shift($keys);

            if ( !$this->exists($this->item, $key) ) {
                $array[$key] = [];
            }

            $array = &$array[$key];
        }

        $array[array_shift($keys)] = $value;
    }

    public function accessible($value)
    {
        return is_array($value) || $value instanceof \ArrayAccess;
    }

    public function exists($array, $key)
    {
        if ($array instanceof \ArrayAccess) {
            return $array->offsetExists($key);
        }

        return isset($array[$key]);
    }

    protected function loadConfigurationFiles()
    {
        $directory = $this->app->make('path.config');

        $paths = new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS);
        $folders = new \RecursiveIteratorIterator($paths);
        foreach ($folders as $file) {
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