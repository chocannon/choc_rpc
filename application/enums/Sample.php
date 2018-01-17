<?php
namespace App\Enums;

class Sample 
{
    const Large = 1;
    const Small = 2;

    public static function map() 
    {
        return [
            self::Large => "大图",
            self::Small => "小图",
        ];
    }

    public static function option() 
    {
        $map = self::map();
        return array_key_exists($key, $map) ? $map[$key] : '';
    }
}