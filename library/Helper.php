<?php
// +----------------------------------------------------------------------
// | 助手函数
// +----------------------------------------------------------------------
// | Author: qh.cao
// +----------------------------------------------------------------------
use App\Exceptions\ServiceException;

class Helper
{
    public static function msectime() {
       list($msec, $sec) = explode(' ', microtime());
       return (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
    }


    public static function hash(array $data, string $appKey)
    {
        
        if (!isset(Config::get('appsolt')[$appKey]['solt'])) {
            throw new ServiceException(__CLASS__ . " : Config Parameter {$appKey}.solt Not Define!");
        }
        $appSolt = Config::get('appsolt')[$appKey]['solt'];
        return md5($appSolt . sha1($appKey . serialize($data)));
    }
}