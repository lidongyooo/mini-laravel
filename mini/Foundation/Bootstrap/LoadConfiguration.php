<?php

namespace Mini\Foundation\Bootstrap;

use Mini\Foundation\Application;

class LoadConfiguration
{
    public function __construct(protected Application $app)
    {
    }

    public function bootstrap()
    {
        $this->app->instance('config', $this);
        $this->loadConfigurationFiles();
    }

    protected function loadConfigurationFiles()
    {
        $files = $this->getConfigurationFiles();
    }

    protected function getConfigurationFiles()
    {
        $files = [];
        $directory = $this->app->make('path.config');

        $paths = new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS);
        $folders = new \RecursiveIteratorIterator($paths);
        foreach ($folders as $file) {
            if ($file->getExtension() === 'php') {
                $files[$this->getAlias($directory, $file)] = require_once $file->getRealPath();
            }
        }

        return $files;
    }

    protected function getAlias($directory, \SplFileInfo $file)
    {
        $basename = str_replace('.php', '', str_replace($directory.DIRECTORY_SEPARATOR, '', $file->getRealPath()));
        return str_replace(DIRECTORY_SEPARATOR, '.', $basename);
    }
}