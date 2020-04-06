<?php

namespace Narnia\Traits;

use Illuminate\Support\Facades\Log;

/**
 * ログを制御するトレイト
 * Laravel からの Monolog
 * Log::channel('slack')->info('Something happened!');
 * Log::stack(['single', 'slack'])->info('Something happened!');
 * MEMO 第2引数の $context は、もう少し用途が確定してから出力を行う
 */
trait LogTrait
{
    public static $channel = null;

    /**
     * デバッグレベルは、大量に出力されるので、クラス名およびキーワードでフィルタを行う
     */
    public static function debug(string $log, ?array $info = []): void
    {
        /*
        // クラス名のフィルタ（ネームスペースも含む）
        $className = get_class();
        $debugPattern = env('LOG_DEBUG_CLASS_PATTERN');
        if ($debugPattern) {
            if (!preg_match($debugPattern, $className)) return;
        }

        // ログ内容のキーワードフィルタ
        $debugKeywordPattern = env('LOG_DEBUG_KEYWORD_PATTERN');
        if ($debugKeywordPattern) {
            if (!preg_match($debugKeywordPattern, $log)) return;
        }

        $message = "{$className} - {$log}";
        $message = static::appendJson($message, $info);
        if ($this->channel) {
            Log::channel($this->channel)->debug($message);
        } else {
            Log::debug($message);
        }
        */
        Log::debug($log);
    }
    public static function info(string $log, ?array $info = []): void
    {
        $className = get_class();
        $message = "{$className} - {$log}";
        $message = static::appendJson($message, $info);
        if (static::$channel) {
            Log::channel(static::$channel)->info($message);
        } else {
            Log::info($message);
        }
    }
    public static function notice(string $log, ?array $info = []): void
    {
        $className = get_class();
        $message = "{$className} - {$log}";
        $message = static::appendJson($message, $info);
        if (static::$channel) {
            Log::channel(static::$channel)->notice($message);
        } else {
            Log::notice($message);
        }
    }
    public static function warning(string $log, ?array $info = []): void
    {
        $className = get_class();
        $message = "{$className} - {$log}";
        $message = static::appendJson($message, $info);
        if (static::$channel) {
            Log::channel(static::$channel)->warning($message);
        } else {
            Log::warning($message);
        }
    }
    public static function error(string $log, ?array $info = []): void
    {
        $className = get_class();
        $message = "{$className} - {$log}";
        $message = static::appendJson($message, $info);
        if (static::$channel) {
            Log::channel(static::$channel)->error($message);
        } else {
            Log::error($message);
        }
    }
    public static function critical(string $log, ?array $info = []): void
    {
        $className = get_class();
        $message = "{$className} - {$log}";
        $message = static::appendJson($message, $info);
        if (static::$channel) {
            Log::channel(static::$channel)->critical($message);
        } else {
            Log::critical($message);
        }
    }
    public static function alert(string $log, ?array $info = []): void
    {
        $className = get_class();
        $message = "{$className} - {$log}";
        $message = static::appendJson($message, $info);
        if (static::$channel) {
            Log::channel(static::$channel)->alert($message);
        } else {
            Log::alert($message);
        }
    }
    public static function emergency(string $log, ?array $info = []): void
    {
        $className = get_class();
        $message = "{$className} - {$log}";
        $message = static::appendJson($message, $info);
        if (static::$channel) {
            Log::channel(static::$channel)->emergency($message);
        } else {
            Log::emergency($message);
        }
    }
    private static function appendJson(string $str, array $info): string
    {
        if (!$info) return $str;

        $appended = $str . " " . static::arrayToJson($info);
        return $appended;
    }
    private static function arrayToJson(array $info): string
    {
        $str = "";
        if ($info) {
            $str = json_encode($info, JSON_UNESCAPED_UNICODE);
        }
        return $str;
    }
}
