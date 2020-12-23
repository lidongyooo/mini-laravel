<?php

namespace Mini\Database;

use Mini\Foundation\ServiceProvider;

class DB extends ServiceProvider
{
    public function output()
    {
        exit('success');
    }

    public function register()
    {
        $this->app->singleton('db', function(){
            return $this;
        });
    }
}