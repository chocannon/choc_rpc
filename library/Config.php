<?php
// +----------------------------------------------------------------------
// | 封装Yaf\Config类获取ini配置文件
// +----------------------------------------------------------------------
// | Author: qh.cao
// +----------------------------------------------------------------------
use RuntimeException;

class Config 
{
    const CONF_PATH = APPLICATION_PATH . '/conf/';
    const SUFFIX    = '.ini';

    public static function get(String $ini, String $prefix = null) 
    {
        $iniFile = self::CONF_PATH . $ini . self::SUFFIX;
        if (!file_exists($iniFile)) {
            throw new RuntimeException("Config File {$$iniFile} Not Found!");
        }

        $config = new Yaf\Config\Ini($iniFile, ini_get('yaf.environ'));
        $result = (null === $prefix) ? $config->get() : $config->get($prefix);
        return ($result instanceof Yaf\Config\Ini) ? $result->toArray() : $result;
    }
}