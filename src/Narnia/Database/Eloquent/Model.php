<?php

namespace Narnia\Database\Eloquent;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Eloquent をベースとした、Modelの基底クラス
 * リクエストキャッシュの制御などを行う
 */
class Model extends \Illuminate\Database\Eloquent\Model
{

    /**
     * @var  array  cached queries
     */
    protected static $_queries_cached = array();

    /**
     * @var  array  array of fetched objects
     */
    protected static $_cached_objects = array();

    /**
     * @var array 説明
     */
    protected static $_descriptions = array();

    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    public static function cacheAll(): array
    {
        $all = static::all();

        $class = get_called_class();
        foreach ($all as $record) {
            $cacheKey = $record->getAttribute($record->getCacheKey);
            static::$_cached_objects[$class][$cacheKey] = $record;
        }
        return $all;
    }

    public static function cacheFind($pk)
    {
        $cacheKey = $pk;

        $class = get_called_class();
        $cached = static::$_cached_objects[$class][$cacheKey] ?? null;
        if ($cached) {
            return $cached;
        }

        $record = static::find($pk);
        if ($record) {
            static::$_cached_objects[$class][$cacheKey] = $record;
        }
        return $record;
    }

    protected static function getCacheKey(string $sql, array $params = []): string
    {
        $implodeParams = static::implodeParams($params);
        $seed = "{$sql}|{$implodeParams}";
        return md5($seed);
    }

    /**
     * Implode the primary keys within the data into a string
     *
     * @param   array
     * @return  string
     */
    protected static function implodeParams(array $params): string
    {
        $implodeParams = implode("\b", $params);
        return $implodeParams;
    }

    protected static function cacheFirst($query)
    {
        $sql    = $query->toSql();
        $params = $query->getBindings();
        $cacheKey = static::getCacheKey($sql, $params);

        $class = get_called_class();
        /*
        $cached = static::$_queries_cached[$class][$cacheKey] ?? null;
        if ($cached){
            return $cached;
        }
        */
        if (array_key_exists($cacheKey, static::$_queries_cached[$class] ?? [])) {
            return $cached = static::$_queries_cached[$class][$cacheKey] ?? null;
        }

        $record = $query->first();                                                                                                                                                                     //if ($record){         // TODO リクエストキャッシュなので、nullもキャッシュする
        static::$_queries_cached[$class][$cacheKey] = $record;
        //}
        return $record;
    }

    public static function loadDescription(): array
    {
        $class = get_called_class();
        $all = static::$_descriptions;
        if (isset($all[$class])) return $all[$class];

        $table = static::getTableName();
        $sql = "SHOW FULL COLUMNS FROM {$table}";
        $columns = DB::select($sql);
        $descs = [];
        foreach ($columns as $column) {
            $key = $column->Field;
            $descs[$key] = (array) $column;
        }
        static::$_descriptions[$class] = $descs;
        return $descs;
    }

    public static function boot()
    {
        parent::boot();

        // XXX 既存データに合わせて null を設定する
        static::saving(function ($model) {
            foreach ($model->attributes as $key => $value) {
                $origin = $model->original[$key] ?? "";
                if ($origin == $value) {
                    // 変更ない項目は、更新対象にしない
                    unset($model->attributes[$key]);
                    continue;
                }
                /*
                if ( !isset($model->original[$key]) || $model->original[$key] === null){
                    $model->{$key} = empty($value) ? null : $value;
                }
                */
            }
        });
    }


    public static function getLastInsertId(): int
    {
        $sql = "SELECT last_insert_id() AS id";
        $ret = DB::selectOne($sql);

        return $ret->id;
    }
}
