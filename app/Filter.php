<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Filter extends Model
{
    protected $primaryKey = null;
    public $separator = ',';
    public static $cacheKey = null;
    
    public static function getFromCache(&$modelIds)
    {
        $models = [];
        foreach($modelIds as $model){
            $models[] = static::$cacheKey . $model;
        }
    
        $models = \Redis::mget($models);
    
        foreach($models as &$model){
            $model = json_decode($model,true);
        }
    
        return $models;
    }
}
