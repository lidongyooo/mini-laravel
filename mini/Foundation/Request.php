<?php

namespace Mini\Foundation;

class Request
{
    public $json = [];

    public $header = [];

    protected $method;

    protected $allowable = ['get', 'post', 'cookie', 'file', 'server', 'header', 'json'];

    protected $pathInfo;

    protected $requestUri;

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

    public function set($property, $key = null, $value = null)
    {
        return $this->$property[$key] = $value;
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

    public function getMethod()
    {
        if ($this->method !== null) {
            return $this->method;
        }

        $this->method = $this->server('REQUEST_METHOD', 'GET');

        if ($this->method !== 'POST') {
            return $this->method;
        }

        if ($overrideMethod = strtoupper($this->post('_method', $this->get('_method', 'POST')))) {
            return $this->method = $overrideMethod;
        }
    }

    public function getRequestUri()
    {
        if (is_null($this->requestUri)) {
            $this->requestUri = $this->prepareRequestUri();
        }

        return $this->requestUri;
    }

    public function getPathInfo()
    {
        if (is_null($this->pathInfo)) {
            $this->pathInfo = $this->preparePathInfo();
        }

        return $this->pathInfo;
    }


    protected function prepareRequestUri()
    {
        if ($requestUri = $this->server('REQUEST_URI', '')) {
            $pos = strpos($requestUri, '#');
            $requestUri = $pos ? substr($requestUri, 0, $pos) : $requestUri;
        }

        $this->set('server', 'REQUEST_URI', $requestUri);

        return $requestUri;
    }

    protected function preparePathInfo()
    {
        $uriComponents = parse_url($this->getRequestUri());
        $pathInfo = $uriComponents['path'] ?? '';

        return $pathInfo[0] === '/' ? substr($pathInfo, 1) : $pathInfo;
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