<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

/**
 * Web Page を制御する基底クラス
 */
abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    //use \LogTrait;

    /**
     * Page を初期化する.
     *
     */
    public function init()
    {
        $this->time_start();

        return $request;
    }

    /**
     * 後処理
     */
    public function destroy()
    {
    }

    /**
     * 後処理
     */
    public function afterProcess()
    {
        $this->time_stop();
    }

    protected function time_start(?string $name = null)
    {
        if (!$name) $name = get_class($this);

        \Debugbar::startMeasure($name);

        $messageStart = "=== {$name}";
        $this->debug($messageStart);
    }

    protected function time_stop(?string $name = null)
    {
        if (!$name) $name = get_class($this);
        \Debugbar::stopMeasure($name);

        $messageStop = "--- {$name}";
        $this->debug($messageStop);
    }

    /**
     * 本当は、dispatch の時点で渡されているはずだが、うまく取れないので Globals から再作成を用意
     * @return Request
     */
    protected function getRequest(): Request
    {
        if (!$this->request) {
            $this->request = Request::capture();
        }
        return $this->request;
    }
    protected function getInput(string $key, $default = null)
    {
        return $this->getRequest()->input($key, $default);
    }

}
