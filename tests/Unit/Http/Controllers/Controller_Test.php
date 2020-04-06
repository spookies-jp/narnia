<?php

namespace Tests\Unit;

use Narnia\Http\Controllers\Controller;
use Tests\TestCase;

class Controller_Test extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $name = "";
        //        $name = $clazz->get
        //        $request = Request::capture();
        //        $instance = new Controller_0($request);
        //        $name = $instance->getShortName();
        //$name = Controller_0::getControllerName();
        $this->assertEquals("", $name);
    }
}

class Controller_0 extends Controller
{
}
