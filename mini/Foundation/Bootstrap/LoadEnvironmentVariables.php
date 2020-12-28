<?php

namespace Mini\Foundation\Bootstrap;

use Mini\Exceptions\Foundation\InvalidPathException;
use Mini\Foundation\Application;

class LoadEnvironmentVariables
{
    protected $filePath;

    public function __construct(protected Application $app)
    {
    }

    public function bootstrap()
    {
        $this->filePath = $this->app->make('path.base').DIRECTORY_SEPARATOR.'.env';
        $this->load();
    }

    protected function load()
    {
        $this->ensureFileIsReadable();

        $lines = $this->readLinesFromFile();
        foreach ($lines as $line) {
            if (!$this->isComment($line) && $this->looksLikeSetter($line)) {
                $this->setEnvironmentVariable($line);
            }
        }
    }

    protected function ensureFileIsReadable()
    {
        if (!is_readable($this->filePath) || !is_file($this->filePath)) {
            throw new InvalidPathException(sprintf('Unable to read the environment file at %s', $this->filePath));
        }
    }

    protected function readLinesFromFile()
    {
        // 将文件读入具有自动检测行结束符的数组
        $autodetect = ini_get('auto_detect_line_endings');
        ini_set('auto_detect_line_endings', '1');
        $lines = file($this->filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        ini_set('auto_detect_line_endings', $autodetect);

        return $lines;
    }

    protected function isComment($line)
    {
        $line = ltrim($line);
        return str_starts_with($line, '#');
    }

    protected function looksLikeSetter($line)
    {
        return str_contains($line, '=');
    }

    protected function setEnvironmentVariable($line)
    {
        [$name, $value] = $this->normaliseEnvironmentVariable($line);
        $_ENV[$name] = $value;
    }

    protected function normaliseEnvironmentVariable($line)
    {
        $explode = explode('=', $line);
        return [$explode[0], $explode[1] ?? ''];
    }
}