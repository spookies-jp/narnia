<?php

namespace Narnia\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

use Narnia\Http\Controllers\TransactionTrait;
use Narnia\Traits\LogTrait;
use Narnia\Traits\SessionTrait;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    use SessionTrait, LogTrait, TransactionTrait;


    /*
     * blade のテンプレートを、routeの名前と同一で呼び出す。
     * 分けたい場合は、第二引数に直接指定する。
     * @param array|null $data
     * @param string|null $view
     * @param array|null $mergedData
     * @return View
     */
    protected function view(?array $data = [], ?string $view = null, ?array $mergedData = []): View
    {
        if (!$view) {
            $route = Route::current();
            $view = $route->getName();
        }
        return view($view, $data, $mergedData);
    }

    /**
     * 負荷テスト用リクエストか
     * ・各controllerでoverrideして条件を指定
     *
     * @return boolean
     */
    protected function isLoadTest(): bool
    {
        return false;
    }
}
