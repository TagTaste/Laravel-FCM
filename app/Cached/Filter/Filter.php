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
        Cache::sAdd("filters:" . $key,$value);
    }
    
    private static function unset(&$key,&$member){
        Cache::sRem("filters:" . $key,$member);
    }
}