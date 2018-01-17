<?php
// +----------------------------------------------------------------------
// | 日志类封装
// +----------------------------------------------------------------------
// | Author: qh.cao
// +----------------------------------------------------------------------
use BadFunctionCallException;

class Logger
{
    const LEVEL  = ['emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info', 'debug'];
    const SUFFIX = '.log';
    private static $instance = null;


    protected function interpolate($message, array $context = [])
    {
        $replace = [];
        array_walk($context, function ($val, $key) use (&$replace) {
            return $replace['{' . $key . '}'] = is_string($val) ? $val : var_export($val, true);
        });
        return strtr($message, $replace);
    }


    protected function log($level, $message, array $context = [])
    {
        $message = '['.date('Y-m-d H:i:s').'] ' . $this->interpolate($message, $context) . "\r\n";
        $logPath = APPLICATION_PATH . '/runtime/log/';
        $logFile = $logPath . $level . '-' . date('Y-m-d') . self::SUFFIX;
        if (!file_exists($logPath)) {
            mkdir($logPath, 0755, true);
        }  
        file_put_contents($logFile , $message, FILE_APPEND|LOCK_EX);
    }


    public function __call($method, array $args = [])
    {
        if (!in_array($method, self::LEVEL)) {
            throw new BadFunctionCallException("Method {$method} Not Found In " . __CLASS__);
        }
        array_unshift($args, $method);
        return call_user_func_array([$this, 'log'], $args);
    }


    public static function __callStatic($method, array $args = [])
    {
        if (!in_array($method, self::LEVEL)) {
            throw new BadFunctionCallException("Method {$method} Not Found In " . __CLASS__);
        }
        if (is_null(self::$instance)) {
            self::$instance = new static;
        }

        array_unshift($args, $method);
        return call_user_func_array([self::$instance, 'log'], $args);
    }
}