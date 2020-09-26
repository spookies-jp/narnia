<?php

namespace Narnia\Services;

use Illuminate\Support\Facades\Cache;
use RuntimeException;

/**
 * キャッシュプロキシ―
 * Laravel のキャッシュの仕組みを、アプリから利用する為に、
 * 挟み込む Proxy として機能する
 * 
 * @var string $origin
 * @var string $driver
 * @var int $ttl
 * @var string[] $tags
 */
class CacheProxy
{

    protected $origin;
    protected $driver;
    protected $ttl = -1;
    protected $tags = [];

    /**
     * Proxy インスタンスを生成する
     * メソッドのキャシュを利用したいクラスで呼び出す
     *
     * @param string $origin
     * @param string|null $driver
     * @param integer|null $ttl
     * @param array|null $tags
     * @return void
     */
    public static function proxy(string $origin, ?string $driver = null, ?int $ttl = -1, ?array $tags = [])
    {
        $proxy = new static($origin, $driver, $ttl, $tags);
        return $proxy;
    }

    /**
     * static な呼び出しは、現在対応していない
     *
     * @param [type] $name
     * @param [type] $arguments
     * @return void
     */
    public static function __callStatic($name, $arguments)
    {
        throw new RuntimeException("callStatic is not supported. Please call instance method. {$name}");
    }

    /**
     * コンストラクタ
     * proxy メソッドと同じだが、インスタンス生成部分は拡張も想定して隠蔽する
     *
     * @param string $origin
     * @param string|null $driver
     * @param integer|null $ttl
     * @param array|null $tags
     */
    public function __construct(string $origin, ?string $driver = null, ?int $ttl = -1, ?array $tags = [])
    {
        $tags[] = $origin;          // オリジナルのクラス名を、タグとして追加する
        $this->origin = $origin;
        $this->driver = $driver;
        $this->ttl = $ttl;
        $this->tags = $tags;
    }

    /**
     * 実体クラスを透過させるためのメソッド
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        $driver = $this->driver;
        $class = $this->origin;
        $ttl   = $this->ttl;
        $tags = $this->tags;

        $cacheName = $this->getCacheKey($class, $name, $arguments);
        $repository = Cache::store($driver);
        if(method_exists($repository->getStore(), 'tags')){
            /** @var \Illuminate\Contracts\Cache\Store\TaggedCache $repository */
            $repository = $repository->tags($tags);
        }

        $func = function () use ($class, $name, $arguments) {
            //echo "Calling static method '$name' " . implode(', ', $arguments). "\n";
            $ret = call_user_func([$class, $name], ...$arguments);
            return $ret;
        };

        if ($ttl < 0) {
            $ret =  $this->rememberForever($repository, $cacheName, $func);
        } else {
            $ret =  $this->remember($repository, $cacheName, $ttl, $func);
        }
        return $ret;
    }
    /**
     * null をキャッシュする為に、rememberForeverを真似て特別処理
     *
     * @param [type] $repository
     * @param string $key
     * @param [type] $callback
     * @return void
     */
    protected function rememberForever($repository, string $key, $callback)
    {
        if ($repository->has($key)){
            return $repository->get($key);
        }

        $repository->forever($key, $value = $callback());

        return $value;
    }
    /**
     * null をキャッシュする為に、rememberを真似て特別処理
     *
     * @param [type] $repository
     * @param string $key
     * @param integer $ttl
     * @param [type] $callback
     * @return void
     */
    protected function remember($repository, string $key, int $ttl, $callback)
    {
        if ($repository->has($key)) {
            return $repository->get($key);
        }

        $repository->put($key, $value = $callback(), $ttl);

        return $value;
    }

    /**
     * キャッシュする為のキー生成
     *
     * @param string $className
     * @param string $funcName
     * @param array $arguments
     * @return string
     */
    public function getCacheKey(string $className, string $funcName, $arguments): string
    {
        $f = function ($x) {
            if (is_array($x)) {
                return "array";
            }
            return $x;
        };

        $a = array_map($f, $arguments);
        $paramKey = implode(',', $a);
        $cacheKey = "_{$className}#{$funcName}({$paramKey})";
        return $cacheKey;
    }

    /**
     * タグ付けによるキャッシュ解放
     *
     * @return void
     */
    public function flush()
    {
        $driver = $this->driver;
        $class = $this->origin;     // コンストラクタで、自身のクラス名は入っているのでオリジナルのクラス名を利用する箇所はない
        $tags = $this->tags;

        $repository = Cache::store($driver);
        if(method_exists($repository->getStore(), 'tags')){
            /** @var \Illuminate\Contracts\Cache\Store\TaggedCache $repository */
            $repository = $repository->tags($tags);
            return $repository->flush();
        }
        return $repository->clear();
    }
}