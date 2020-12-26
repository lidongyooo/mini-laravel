<?php

namespace Mini\Foundation;

class Pipeline
{
    protected $passable, $pipes;

    public function __construct(protected Application $app)
    {
    }

    public function send($passable)
    {
        $this->passable = $passable;
        return $this;
    }

    public function through($pipes)
    {
        $this->pipes = $pipes;
        return $this;
    }

    public function then(\Closure $destination)
    {
        $pipeline = array_reduce(
            array_reverse($this->pipes), [$this, 'carry'], $destination
        );
        return $pipeline($this->passable);
    }

    protected function carry($stack, $pipe)
    {
        return function ($passable) use ($stack, $pipe) {
            $pipe = $this->app->make($pipe);
            return $pipe->handle($passable, $stack);
        };
    }



}