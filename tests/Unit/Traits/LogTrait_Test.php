<?php

namespace Tests\Unit;

use Narnia\Traits\LogTrait;
use Tests\TestCase;

class LogTrait_Test extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        /*
        $mock = app(LogTrait_0::class);

        $this->instance(LogTrait_0::class, $mock);

        $instance = new LogTrait_0();
        dd($instance);
        $instance->error("error");

        LogTrait_0::info("test");
        */
        $name = "";
        //        $name = $clazz->get
        //        $request = Request::capture();
        //        $instance = new Controller_0($request);
        //        $name = $instance->getShortName();
        //$name = Controller_0::getControllerName();
        $this->assertEquals("", $name);
    }
}

class LogTrait_0
{
    use LogTrait;
}
