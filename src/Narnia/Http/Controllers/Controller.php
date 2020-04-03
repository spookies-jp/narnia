<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Traits\SessionTrait;
use App\Traits\LogTrait;
use App\Traits\TransactionTrait;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;
use Laravel\Socialite\One\User;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

use ReflectionClass;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    use SessionTrait, LogTrait, TransactionTrait;


    /**
     * コントローラのインスタンス生成時処理
     *
     * @override
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        // $this->middleware('auth:accounts');
    }


    /**
     * Get controller path.
     *
     * @return string
     */
    public static function getControllerName(): string
    {
        return (new ReflectionClass(static::class))->getShortName();
    }

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
     * 負荷テスト用ユーザーか
     *
     * @return boolean
     */
    public function isLoadTestUser(): bool
    {
        // 本番は必ずfalses
        if (app()->isProduction()) {
            return false;
        }

        return $this->isLoadTestable();
    }

    /**
     * 負荷テスト可能か
     * ・各controllerでoverrideして条件を指定
     *
     * @return boolean
     */
    protected function isLoadTestable(): bool
    {
        return false;
    }
}
