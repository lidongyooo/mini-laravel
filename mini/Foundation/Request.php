<?php

namespace Mini\Foundation;

class Request
{

    public function __construct(public $get, public $post, public $cookie, public $files, public $server)
    {

    }

    public static function capture()
    {
        return new self($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER);
    }
}