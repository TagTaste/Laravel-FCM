<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Filter extends Model
{
    protected $primaryKey = null;
    public $separator = ',';
    public static $cacheKey = null;
    
    protected $csv = [];
    
    protected $strings = [];
    
    protected $models = [];
    
    public static $relatedColumn = null;
    
    
    public static function addKey($relatedColumnId, $key, $value, $delimiter=false)
    {
        if(!$delimiter){
            return static::insert(
                [
                    static::$relatedColumn => $relatedColumnId,
                    'key' => $key,
                    'value' => $value
                ]);
        }
        
        $data = [];
        $value = explode(',',$value);
        foreach($value as $v){
            $data[] = [
                static::$relatedColumn=>$relatedColumnId,
                'key' => $key,
                'value' => $v
            ];
        }
        
        return static::insert($data);
    }
    
    public static function removeKey($relatedColumnId,$key,$value = null)
    {
        $filter = static::where(static::$relatedColumn,$relatedColumnId)
            ->where('key',$key);
        
        if($value){
            $filter = $filter->where('value',$value);
        }
        
        return $filter->delete();
    }
    
    public static function updateKey($relatedColumnId, $key, $value, $separator=false)
    {
        static::removeKey($relatedColumnId,$key);
        
        //create new filter
        return static::addKey($relatedColumnId,$key,$value,$separator);
        
    }
    
    public static function addModel($model)
    {
        $self = new static;
        foreach($self->csv as $filter){
            if(isset($model->{$filter})){
                static::updateKey($model->id,$filter,$model->{$filter},',');
            }
        }
        
        foreach($self->strings as $filter){
            if(isset($model->{$filter})){
                static::updateKey($model->id,$filter,$model->{$filter});
            }
        }
        
        foreach($self->models as $filter){
            list($relationship,$attribute) = explode(".",$filter);
            
                try {
                    foreach($model->$relationship()->get() as $rel){
                        if(isset($rel->$attribute)){
                            static::updateKey($model->id,$attribute,$rel->$attribute);
                        }
                    }
                } catch (\Exception $e){
                    \Log::error($e->getMessage() . " : " . $e->getFile() . ": " . $e->getLine());
                }
                
            
            
        }
        
    }
    
    public static function removeModel($modelId)
    {
        return static::where(static::$relatedColumn,$modelId)->delete();
    }
    
    public static function getFilters($model = null)
    {
        $filter = static::class;
        if($model){
            $filter = "\\App\\Filter\\" . ucfirst($model);
        }
        $filters = $filter::select('key','value',\DB::raw('count(`key`) as count'))
            ->groupBy('key','value')->orderBy('count','desc')->take(10)->get()->groupBy('key');
        
        foreach($filters as $key=>&$sub){
            foreach($sub as &$filter){
                unset($filter->key);
            }
        }
        return $filters;
    }
    
    public static function getModels($filters)
    {
        $models = null;
        foreach($filters as $filter => $value){
            
            $model = static::selectRaw('distinct ' . static::$relatedColumn)->where('key',$filter)->whereIn('value',$value)->get()
                ->pluck(static::$relatedColumn);
            if(is_null($models)){
                $models = $model;
                continue;
            }
            $models = $model->intersect($models);
        }
        
        if(count($models) == 0){
            return $models;
        }
        
        return static::getFromCache($models);
    }
    
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
