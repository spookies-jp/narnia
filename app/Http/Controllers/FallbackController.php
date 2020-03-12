<?php

namespace App\Http\Controllers;

/**
 * fallback コントローラクラス
 */
class FallbackController extends Controller
{
    /*
    public function init() {
        // 余分なことはしない

    }
    */

    /**
     * Laravel に移植していないPageコントーラの殆どの処理が、ここを通る
     */
    public function index()
    {
        $request = $this->getRequest();
        $uri = $request->server->get('REQUEST_URI');
        if (substr($uri, -9) != 'index.php' && substr($uri, -4) == '.php') {
            return;     // php 呼び出しの場合は、 require でも何もしない
        }

        $dispatcher = new \SC_Dispatcher();
        return $dispatcher->dispatch($request);
    }
}
