<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Controller;

/**
 * 管理画面の基底コントローラクラス
 */
class AdminController extends Controller
{

    public function index()
    {
    }

    protected function beginTransaction()
    {
        DB::beginTransaction();
    }
    protected function commitTransaction()
    {
        DB::commit();
    }
    protected function rollbackTransaction()
    {
        DB::rollBack();
    }

}
