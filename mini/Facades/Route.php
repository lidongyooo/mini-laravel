<?php

namespace Mini\Facades;

class Route extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'router';
    }

}