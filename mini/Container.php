<?php
namespace Mini;

use Psr\Container\ContainerInterface;

class Container implements ContainerInterface, \ArrayAccess
{
    public function output()
    {
        echo 'container';
    }

    public function bind($abstract, $concrete, $share = false)
    {

    }

    public function make($abstract, $concrete)
    {

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