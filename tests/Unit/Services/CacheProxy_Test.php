<?php

namespace Tests\Unit\Services;

use Narnia\Services\CacheProxy;
use Tests\TestCase;

class CacheProxy_Test extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {

        for($i = 0; $i < 10; $i++) {
           echo CacheProxy_0::cache()::getRandom() . "\n";
           echo CacheProxy_0::getRandom() . "\n";
        }

        $this->assertTrue(true);
    }
}

class CacheProxy_0 extends CacheProxy
{
    public static function getRandom(): int
    {
        return random_int(1, 1000);
    }
    public static function cache()
    {
        return static::proxy(get_called_class(), "array", 1000);
    }
}
