<?php

namespace Mini\Routing;

class RouteGroup
{

    public static function merge($new, $old)
    {
        $new = [
            'namespace' => static::formatNamespace($new, $old),
            'prefix' => static::formatPrefix($new, $old),
        ];

        return $new;
    }

    protected static function formatNamespace($new, $old)
    {
        if (isset($new['namespace'])) {
            return isset($old['namespace']) && !str_starts_with($new['namespace'], '\\')
                ? trim($old['namespace'], '\\').'\\'.trim($new['namespace'], '\\')
                : trim($new['namespace'], '\\');
        }

        return $old['namespace'] ?? null;
    }

    protected static function formatPrefix($new, $old)
    {
        $old = $old['prefix'] ?? null;

        return isset($new['prefix']) ? trim($old, '/').'/'.trim($new['prefix'], '/') : $old;
    }

}