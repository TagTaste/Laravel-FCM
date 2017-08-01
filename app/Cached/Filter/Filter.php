<?php

namespace App\Cached\Filter;

use \Redis as Cache;
class Filter
{
    public function __construct($key,$value)
    {
        $this->set($key,$value);
    }
    
    private function set(&$key,&$value){
        if(strpos($value,",") === -1){
            Cache::sAdd("filters:" . $key,$value);
            return;
        }
    
        $values = explode(",",$value);
        Cache::sAddArray($key,$values);
    }
    
    private function addToCache(){
    
    }
    
    private static function unset(&$key,&$member){
        Cache::sRem("filters:" . $key,$member);
    }
    
}