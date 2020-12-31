<?php

namespace Mini\Exceptions\Routing;

class NotFoundHttpException extends \Exception
{
    public function __construct(string $message = null, \Throwable $previous = null, int $code = 0)
    {
        parent::__construct($message, 404, $previous);
    }
}