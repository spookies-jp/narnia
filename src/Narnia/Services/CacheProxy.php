<?php

namespace Narnia\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * キャッシュプロキシ―
 */
class CacheProxy
{

    protected static $origin;
    protected static $driver;
    protected static $ttl;

    public static function proxy($origin, ?string $driver = null, ?int $ttl = -1)
    {
        if (static::$origin) {
            Log::debug("old cache : " . static::$origin);
        }

        $class = get_called_class();
        $class::$origin = $origin;
        $class::$driver = $driver;
        $class::$ttl = $ttl;
        return $class;
    }
    public static function reset()
    {
        static::$origin = null;
        static::$driver = null;
        static::$ttl = 0;
    }

    public static function __callStatic($name, $arguments)
    {
        $driver = static::$driver;
        $class = static::$origin;
        $ttl   = static::$ttl;
        $store = "Cache";

        $cacheName = static::getCacheKey($class, $name, $arguments);
        if ($driver) {
            $store = Cache::store($driver);
        }

        $func = function () use ($class, $name, $arguments) {
            //echo "Calling static method '$name' " . implode(', ', $arguments). "\n";
            $ret = call_user_func([$class, $name], ...$arguments);
            return $ret;
        };

        if ($ttl < 0) {
            $ret = call_user_func([$store, 'rememberForever'], $cacheName, $func);
        } else {
            $ret = call_user_func([$store, 'remember'], $cacheName, $ttl, $func);
        }
        static::reset();
        return $ret;
    }

    public static function getCacheKey($className, $funcName, $arguments): string
    {
        $f = function ($x) {
            if (is_array($x)) {
                return "array";
            }
            return $x;
        };

        $a = array_map($f, $arguments);
        $paramKey = implode(',', $a);
        $cacheKey = "_{$className}#{$funcName}({$paramKey})";
        return $cacheKey;
    }
}
