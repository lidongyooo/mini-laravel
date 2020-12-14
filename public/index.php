<?php

require_once __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap.php';

$kernel = $app->make(\Mini\Interfaces\Foundation\KernelContact::class);

var_dump($kernel);