<?php
// +----------------------------------------------------------------------
// | 封装Illuminate\Validation类验证参数
// +----------------------------------------------------------------------
// | Author: qh.cao
// +----------------------------------------------------------------------
use RuntimeException;
use Illuminate\Validation\Factory;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;

class Validation extends Factory 
{
    const DS           = DIRECTORY_SEPARATOR;
    const TRANS_PATH   = APPLICATION_PATH . self::DS . 'lang';
    const TRANS_LOCALE = 'zh-CN';
    const VALID_PATH   = APPLICATION_PATH . self::DS . 'application' . self::DS . 'validations';
    const SUFFIX       = '.php';

    private static $validator = null;

    public static function getInstance() 
    {
        if (null === self::$validator) {
            $transLoader     = new FileLoader(new Filesystem, self::TRANS_PATH);
            $translator      = new Translator($transLoader, self::TRANS_LOCALE);
            self::$validator = new Factory($translator);
        }

        return self::$validator;
    }


    /**
     * 检测请求参数
     * @param  Yaf\Request\Http $request 请求实例
     * @return array                     结果数组
     */
    public static function check(Yaf\Request\Http $request) 
    {
        $moduleName = $request->getModuleName();
        $contrlName = $request->getControllerName();
        $actionName = $request->getActionName();

        $validFile  = self::VALID_PATH . self::DS . $moduleName . self::DS . $contrlName . self::SUFFIX;
        if (!file_exists($validFile)) {
            throw new RuntimeException("Validation File {$validFile} Not Found!");
        }

        $validations = require $validFile;
        if (!is_array($validations)) {
            throw new RuntimeException("Validation File {$validFile} Not Available!");
        }
        
        if (array_key_exists($actionName, $validations)) {
            $rules = $attrs = [];
            if (isset($validations[$actionName]['rules'])) {
                $rules = $validations[$actionName]['rules'];
            }
            if (isset($validations[$actionName]['attributes'])) {
                $attrs = $validations[$actionName]['attributes'];
            }
            $validator = self::getInstance()->make($request->getParams(), $rules, [], $attrs);
            if ($validator->fails()) {
                return [false, $validator->messages()->first()];
            }
        }
        return [true, ''];
    }
}