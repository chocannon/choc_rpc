<?php
// +----------------------------------------------------------------------
// | 助手函数
// +----------------------------------------------------------------------
// | Author: qh.cao
// +----------------------------------------------------------------------
class Helper
{
    public static function msectime() {
       list($msec, $sec) = explode(' ', microtime());
       return (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
    }
}