<?php

namespace Narnia\Http\Controllers;

use App\Http\Controllers\Auth\TwitterController;
use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Traits\LogTrait;
use Illuminate\Support\Arr;

trait TransactionTrait
{
    use LogTrait;

    /**
     * 負荷テストでのロールバックの例外
     *
     * @var array
     */
    protected $exceptRollbackForLoadTest = [
        // Controller名 => [Action名1,Action名2,...]

        TwitterController::class => [
            'handleProviderCallback'
        ]
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure  $next
     * @return mixed
     */
    public function startTransaction()
    {
        $this->debug("transaction->start()");
        DB::beginTransaction();
    }

    public function endTransaction()
    {
        // XXX 負荷テストで必要であればロールバック
        if ($this->shouldRollbackForLoadTest()) {
            $this->rollbackTransaction();
        } else {
            $this->commitTransaction();
        }
    }
    public function rollbackTransaction()
    {
        $this->debug("transaction->rollback()");
        DB::rollBack();
    }
    public function commitTransaction()
    {
        $this->debug("transaction->commit()");
        DB::commit();
    }

    /**
     * 負荷テストユーザーか
     *
     * @return boolean
     */
    protected function shouldRollbackForLoadTest()
    {
        $currentController = get_class(Route::current()->controller);
        // 例外判定
        $exceptActions = Arr::get($this->exceptRollbackForLoadTest, $currentController, null);
        if ($exceptActions) {
            $currentAction = request()->route()->getActionMethod();
            if (in_array($currentAction, $exceptActions)) {
                return false;
            }
        }

        // 負荷テストユーザー判定
        return $this->isLoadTestUser();
    }
}
