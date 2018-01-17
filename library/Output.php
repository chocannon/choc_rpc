<?php
// +----------------------------------------------------------------------
// | 格式化输出封装
// +----------------------------------------------------------------------
// | Author: qh.cao
// +----------------------------------------------------------------------
class Output
{
    protected static $code    = 0;
    protected static $message = 'Successful';
    protected static $data    = [];


    public static function json(...$args)
    {
        $obj = new stdClass;
        switch (count($args)) {
            case 0:
                $obj->code      = self::$code;
                $obj->message   = self::$message;
                $obj->data      = (object)self::$data;
                break;
            case 1:
                $obj->code      = self::$code;
                $obj->message   = self::$message;
                $obj->data      = (object)$args[0];
                break;
            default:
                $obj->code      = (int)$args[0];
                $obj->message   = (string)$args[1];
                $obj->data      = (object)self::$data;
                break;
        }
        $obj->timestamp = time();
        return json_encode($obj, JSON_UNESCAPED_UNICODE);
    }
}