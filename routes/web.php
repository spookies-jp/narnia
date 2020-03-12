<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* Admin */
Route::namespace('Admin')->group(function () {
    // "App\Http\Controllers\Admin"名前空間下のコントローラ
    Route::any('/admin/home'                            , 'AdminHomeController@index');
    Route::any('/admin/info'                            , 'AdminHomeController@php_info');
});

//Route::any('{catchall}', "FallbackController@index")->where('catchall', '.*')->fallback();
Route::any('{catchall}', "FallbackController@index")->where('catchall', '.*');

/** fallback 機能が、GETしか対応していない
Route::fallback(function () {
    //
    if (isset($_GET['url']) && $_GET['url'] === 'favicon.ico') {
        return;
    } else {

        $url = $_GET['url'];

        $dispatcher = new SC_Dispatcher();
        $dispatcher->dispatch($url);
    }
}, ['GET', 'POST']);
*/

