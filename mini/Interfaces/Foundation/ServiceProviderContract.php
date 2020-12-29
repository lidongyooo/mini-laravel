<?php

namespace Mini\Interfaces\Foundation;

interface ServiceProviderContract
{
    public function register();

    public function boot();
}