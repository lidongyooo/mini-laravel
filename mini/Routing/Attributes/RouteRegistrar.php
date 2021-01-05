<?php

namespace Mini\Routing\Attributes;

use Mini\Interfaces\Routing\Attributes\RouteAttribute;
use Mini\Routing\Router;

class RouteRegistrar
{
    protected string $directory;

    public function __construct(protected Router $router, protected string $rootNamespace)
    {
    }

    public function registerDirectory($directory)
    {
        $this->directory = $directory;

        $folders = new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new \RecursiveIteratorIterator($folders);

        foreach ($files as $file) {
            if ($file->getExtension() === 'php') {
               $this->processAttributes($this->fullQualifiedClassNameFromFile($file));
            }
        }

    }

    protected function fullQualifiedClassNameFromFile(\SplFileInfo $file)
    {
        return $this->rootNamespace.'\\'.trim(str_replace([$this->directory, '.php'], '' , $file->getRealPath()), DIRECTORY_SEPARATOR);
    }

    protected function processAttributes($className)
    {
        if (!class_exists($className)) {
            return;
        }

        $class = new \ReflectionClass($className);
        $classRouteAttributes = new ClassRouteAttribute($class);

        foreach ($class->getMethods() as $method) {
            $attributes = $method->getAttributes(RouteAttribute::class, \ReflectionAttribute::IS_INSTANCEOF);

            foreach ($attributes as $attribute) {
                $attributeClass = $attribute->newInstance();
                $this->registerRoute($class, $classRouteAttributes, $attributeClass, $method);
            }

        }

    }

    protected function registerRoute(\ReflectionClass $reflectionClass,ClassRouteAttribute $classRouteAttributes,RouteAttribute $attributeClass,\ReflectionMethod $reflectionMethod)
    {

        if ($prefix = $classRouteAttributes->prefix()) {
            $attributeClass->uri = trim($prefix, '/'). '/' .trim($attributeClass->uri, '/');
        }
        $middleware = array_merge($classRouteAttributes->middleware(), $attributeClass->middleware);

        $httpMethod = $attributeClass->method;
        $this->router->$httpMethod($attributeClass->uri, [$reflectionClass->getName(), $reflectionMethod->getName()])->middleware($middleware);

    }

}