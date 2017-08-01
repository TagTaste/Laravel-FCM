<?php

namespace App\Cached\Filter;


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
    
    protected function boot(){
        $attributes = [];
        //save name of the filters
        foreach($this->attributes as $attribute){
            if(empty($this->model->$attribute)){
                \Log::info("empty $attribute");
                continue;
            }
    
            $attr = $this->model->$attribute;
    
            //sanitize
            $key = "filters:{$this->modelName}:$attribute";
            \Log::info($key);
            if(strpos($attr,",") === false){
                \Redis::sAdd($key,$attr);
                $attributes[] = $attr;
                
            } else {
                $attrs = explode(",",$attr);
                //not used sAddArray since it doesn't provide a way to check which items of array were added.
                //it just returns count.
                foreach($attrs as $att){
                    \Redis::sAdd($key,$att);
                    $attributes[] = $att;
                }
            }
        }
        
        //map filters to ids
        if(!empty($attributes)){
            $id = $this->model->id;
            foreach($attributes as $attr){
                $key = "data:{$this->modelName}:$attr";
               \Redis::sAdd($key,$id);
            }
        }
    }
    
    public static function getModelIds($keys)
    {
        $self = new static();
        if(is_string($keys)){
            $keys = "data:{$self->modelName}:$keys";
            return \Redis::sMembers($keys);
        }
        foreach($keys as $index => &$key){
            $key = "data:{$self->modelName}:$key";
        }
        return \Redis::sInter(...$keys);
    }
    
    public static function getFilters()
    {
        $self = new static();
        $filters = [];
        \Log::info($self->attributes);
        foreach($self->attributes as $attribute){
            $key = "filters:" . $self->modelName . ":" . $attribute;
            \Log::info($key);
            $filters[$attribute] = \Redis::sMembers($key);
        }
        return $filters;
    }
    
    
}