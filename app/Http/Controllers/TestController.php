<?php

namespace App\Http\Controllers;

use Mini\Foundation\Request;

class TestController extends Controller
{
    public function output(Request $request)
    {
        return 'I did it'.PHP_EOL;
    }
}