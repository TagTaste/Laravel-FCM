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
                continue;
            }
    
            $attr = $this->model->$attribute;
    
            //sanitize
            $key = "filters:{$this->modelName}:$attribute";
            if(strpos($attr,",") === false){
                //only map if the key gets added.
                \Redis::sAdd($key,$attr) ? $attributes[] = $attr : null;
                
            } else {
                $attrs = explode(",",$attr);
                //not used sAddArray since it doesn't provide a way to check which items of array were added.
                //it just returns count.
                foreach($attrs as $att){
                    \Redis::sAdd($key,$att) ? $attributes[] = $attr : null;
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
    
    public static function getModelIds($key)
    {
        $self = new static();
        $key = "data:{$self->modelName}:$key";
        return \Redis::sMembers($key);
    }
    
    public static function getFilters()
    {
        $self = new static();
        $filters = [];
        $key = "filters:" . $self->modelName;
        \Log::info($key);
        foreach($self->attributes as $attribute){
            $key .= ":" . $attribute;
            $filters[$attribute] = \Redis::sMembers($key);
        }
        return $filters;
    }
    
    
}