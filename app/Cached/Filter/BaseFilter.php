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
        //union if same filter name,
        //intersect different filter names
        $union = [];
        $intersect = [];
        $prefix = "data:{$self->modelName}:";
        $uniqueKey = time() . str_random(10);
        $remove = [];

        foreach($keys as $name => $value){
            
            if(is_string($value)){
                $value = $prefix . $value;
                $intersect[] = $value;
            }
            if(is_array($value)){
                foreach($value as &$k){
                    $k = $prefix.$k;
                }
                $key = $uniqueKey . "union" . str_random(2);
                $intersect[] = $key;
                $remove[] = $key;
                \Redis::sUnionStore($key,...$value);
            }
        }
        
        $modelIds = \Redis::sInter(...$intersect);
        if(!empty($remove)){\Redis::del($remove);}
        return $modelIds;
    }
    
    public static function getFilters()
    {
        $self = new static();
        $filters = [];

        foreach($self->attributes as $attribute){
            $key = "filters:" . $self->modelName . ":" . $attribute;
            $filters[$attribute] = \Redis::sMembers($key);
        }
        return $filters;
    }
    
    
}