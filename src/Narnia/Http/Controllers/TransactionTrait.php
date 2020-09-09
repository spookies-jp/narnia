<?php

namespace Narnia\Http\Controllers;

use Illuminate\Support\Facades\DB;

trait TransactionTrait
{
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
}
