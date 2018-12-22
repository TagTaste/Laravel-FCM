<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Filter extends Model
{
    private static $maxFilters = 6;
    protected $primaryKey = null;
    public $separator = ',';
    public static $cacheKey = null;
    
    protected $csv = [];
    
    protected $strings = [];
    
    protected $models = [];

    public static $filterOrder = [];

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
        $label = $key;
        if(is_array($key)){
            $label =  array_key($key);
        }
        static::removeKey($relatedColumnId,$label);
        
        //create new filter
        return static::addKey($relatedColumnId,$label,$value,$separator);
    }
    
    public static function removeAllKeys($relatedColumnId)
    {
        return static::where(static::$relatedColumn,$relatedColumnId)
            ->delete();
    }
    
    public static function addModel($model)
    {   
        $self = new static;
        $self::removeAllKeys($model->id);
        foreach($self->csv as $label => $filter){
            if(is_int($label)){
                $label = $filter;
            }
            $method = "get{$filter}attribute";
            $value = null;
            if(method_exists($self,$method)){
                $value = $self->$method($model) ;
            } elseif(isset($model->{$filter})){
               $value = $model->{$filter};
            }
            
            if($value) {
                static::addKey($model->id,$label,$value,',');
            }
            
        }
        
        foreach($self->strings as $label => $filter){
            if(is_int($label)){
                $label = $filter;
            }
            
            $value = null;
            $method = "get{$filter}attribute";
            
            if(method_exists($self,$method)){
                $value = $self->$method($model) ;
            } elseif(isset($model->{$filter})){
                $value = $model->{$filter};
            }
            
            if($value){
                static::addKey($model->id,$label,$value);
            }
        }
        
        foreach($self->models as $label => $filter){
           
            list($relationship,$attribute) = explode(".",$filter);
            
                try {
                    $related = $model->$relationship()->get();
                    if($related){
                        if(is_int($label)){
                            $label = $relationship;
                        }
                        foreach($related as $rel){
                            
                            $value = null;
                            $method = "get" . str_replace(".","_",$filter) . "attribute";
                            
                            if(method_exists($self,$method)){
                                $value = $self->$method($model) ;
                            } elseif(isset($rel->$attribute)){
                                $value = $rel->$attribute;
                            }
                            if($value){
                                static::addKey($model->id,$label,$value);
                            }
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
        $filterClass = static::class;
        if($model){
            $filterClass = "\\App\\Filter\\" . ucfirst($model);
        }
        $allFilters = $filterClass::select('key','value',\DB::raw('count(`key`) as count'))
            ->groupBy('key','value')->orderBy('count','desc')->get()->groupBy('key');
        $filters = [];
        //$allFilters = $allFilters->keyBy('key');
        $order = $filterClass::$filterOrder;

        if(count($order))
        {
            foreach($order as $key){
                $count = 0;
                $singleFilter = $allFilters->get($key);
                if(!$singleFilter)
                {
                    continue;
                }
                foreach($singleFilter as &$filter)
                {
                    if(!array_key_exists($key,$filters)){
                        $filters[$key] = [];
                    }
                    if(!array_key_exists($key,$filters)){
                        $filters[$key] = [];
                    }

                    $filters[$key][] = ['value' => $filter->value,'count'=>$filter->count];
                    $count++;
                    if($count >= static::$maxFilters){
                        break;
                    }
                }
            }

        }
        else
        {
            foreach($allFilters as $key=>&$sub){
                $count = 0;
                foreach($sub as &$filter){
                    $filters[$key][] = ['value' => $filter->value,'count'=>$filter->count];
                    $count++;
                    if($count >= static::$maxFilters){
                        break;
                    }
                }
            }
        }

        return $filters;
    }
    
    
    public static function getModelIds(&$filters,$skip = null,$take = null)
    {
        $models = null;
        foreach($filters as $filter => $value){
            $model = static::selectRaw('distinct ' . static::$relatedColumn)
                ->where('key',$filter)->whereIn('value',$value);
            
            if((null !== $skip) || (null !== $take)){
                $model = $model->skip($skip)->take($take);
            }
           
            $model = $model->orderBy(static::$relatedColumn)
                ->get()
                ->pluck(static::$relatedColumn);
            
            if(is_null($models)){
                $models = $model;
                continue;
            }
            
            $models = $model->intersect($models);
        }
        return $models;
    }
    public static function getModels($filters, $skip = null, $take = null)
    {
        $models = static::getModelIds($filters,$skip,$take);
        
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
