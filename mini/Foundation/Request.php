<?php

namespace Mini\Foundation;

class Request
{
    public $json = [];
    public $header = [];

    protected $allowable = ['get', 'post', 'cookie', 'file', 'server', 'header', 'json'];

    public function __construct(public $get, public $post, public $cookie, public $file, public $server)
    {
        $this->header = $this->getHeaders();
    }

    public static function capture()
    {
        return new self($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER);
    }

    public function got($property, $key = null, $default = null)
    {
        $parameters = $this->$property;
        if ($key === null) {
            return $parameters;
        }

        return $parameters[$key] ?? $default;
    }

    public function isJson()
    {
        return str_contains($this->header('CONTENT_TYPE'), 'json');
    }

    public function jsonBootstrap()
    {
        if ($this->isJson()) {
            $this->json = json_decode(file_get_contents('php://input'), 1);
        }

        return $this->json;
    }

    protected function getHeaders()
    {
        $headers = [];
        foreach ($this->server as $key => $value) {
            if (str_starts_with($key, 'HTTP_')) {
                $headers[substr($key, 5)] = $value;
            } elseif (\in_array($key, ['CONTENT_TYPE', 'CONTENT_LENGTH', 'CONTENT_MD5'])) {
                $headers[$key] = $value;
            }
        }
        return $headers;
    }

    public function __call($name, $arguments)
    {
        if (in_array($name, $this->allowable)) {
            return $this->got($name, ...$arguments);
        }

        throw new \BadMethodCallException('Call to undefined method '. $this::class .'::'.$name);
    }
}