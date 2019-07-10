<?php

namespace App\Cached\Filter;
use Illuminate\Support\Facades\Redis;

class BaseFilter
{
    protected $attributes = []; //attributes to add to cache;
    protected $modelName = null;
    protected $model = null;
    
    public function __construct($model = null)
    {
        $this->setModelName();
        if($model){
            $this->model = $model;
            $this->boot();
        }
    }
    
    protected function setModelName(){
        $this->modelName = strtolower(class_basename(static::class));
    }
    
    protected function boot()
    {
        foreach($this->attributes as $filter){
            $method = "getValueOf" . ucfirst($filter);
            if(method_exists($this,$method)){
                $value = $this->$method();
            } else {
                $value = $this->{$filter};
            }
            if(!$value){
                continue;
            }
            if(is_array($value)){
                $this->addArray($filter,$value);
                continue;
            }
            
            $this->addFilter($filter,$value);
        }
    }
    
    public function addArray($filterName, &$filterValues = array())
    {
        foreach($filterValues as $value){
            $this->addFilter($filterName,$value);
        }
    }
    
    public function addFilter(&$filterName,$value)
    {
        if(empty($value) || empty($filterName)){
            return;
        }
        $value = trim($value);
        Redis::sAdd("filters:" . $this->modelName . ":" . $filterName,$value);
        Redis::sAdd("data:" . $this->modelName . ":$filterName:$value",$this->model->id);
    }
    
    public static function getModelIds($keys)
    {
        $self = new static();
        if(is_string($keys)){
            $keys = "data:{$self->modelName}:$keys";
            return Redis::sMembers($keys);
        }
        //union if same filter name,
        //intersect different filter names
        $union = [];
        $intersect = [];
       
        $uniqueKey = time() . str_random(10);
        $remove = [];

        foreach($keys as $name => $value){
            $prefix = "data:{$self->modelName}:$name:";
            if(is_string($value)){
                $value = $prefix . $value;
                $intersect[] = $value;
                continue;
            }
            if(is_array($value)){
                foreach($value as &$k){
                    $k = $prefix.$k;
                }
                $key = $uniqueKey . "union" . str_random(2);
                $intersect[] = $key;
                $remove[] = $key;
                Redis::sUnionStore($key,...$value);
            }
        }
        $modelIds = Redis::sInter(...$intersect);
        if(!empty($remove)){Redis::del($remove);}
        return $modelIds;
    }
    
    public static function getFilters()
    {
        $self = new static();
        $filters = [];

        foreach($self->attributes as $attribute){
            $key = "filters:" . $self->modelName . ":" . $attribute;
            $filters[$attribute] = Redis::sMembers($key);
        }
        return $filters;
    }
    
    
}