<?php

namespace App\Cached;

use \Redis as Cache;

class Filter
{
    protected $prefix = "";
    public function __construct($modelName, $attribute, $value, $prefix = null)
    {
        $attribute = $modelName . ":" . $attribute;
        $this->set($attribute,$value);
    }
    
    private function set(&$key,&$value){
        if(strpos($value,",") === -1){
            Cache::sAdd($prefix . $key,$value);
            return;
        }
    
        $values = explode(",",$value);
        Cache::sAddArray($key,$values);
    }
    
    private static function unset(&$key,&$member){
        Cache::sRem($prefix . $key,$member);
    }
}