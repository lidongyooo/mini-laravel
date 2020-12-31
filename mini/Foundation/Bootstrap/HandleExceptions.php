<?php

namespace Mini\Foundation\Bootstrap;

use Mini\Foundation\Application;

class HandleExceptions
{
    public function __construct(protected Application $app)
    {
    }

    public function bootstrap()
    {
        error_reporting(E_ALL);

        set_error_handler([$this, 'handleError']);

        set_exception_handler([$this, 'handleException']);

        register_shutdown_function([$this, 'handleShutdown']);

        if ( !in_array(config('app.env','local'), ['local', 'testing']) ) {
            ini_set('display_errors', 'Off');
        }
    }

    public function handleError($level, $message, $file = '', $line = 0)
    {
        if (error_reporting() & $level) {
            throw new \ErrorException($message, 0, $level, $file, $line);
        }
    }

    public function handleException(\Throwable $e)
    {
        http_response_code($e->getCode());
        die(sprintf("Captured Throwable: %s in %s on line %s", $e->getMessage(), $e->getFile(), $e->getLine()));
    }

    public function handleShutdown()
    {
        if ( !is_null($error = error_get_last()) && $this->isFatal($error['type'])) {
            throw new \ErrorException($error['message']);
        }
    }

    protected function isFatal($type)
    {
        return in_array($type, [E_COMPILE_ERROR, E_CORE_ERROR, E_ERROR, E_PARSE]);
    }
}