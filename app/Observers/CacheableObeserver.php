<?php namespace App\Observers;

use Illuminate\Support\Facades\Redis;
class CacheableObeserver
{
    public function created($model)
    {
        \App\Cacheable::set($model);
        //\App\Cacheable::sadd($model,$this->getCollectionKey($model));
    }
    
    public function updated($model){
        Redis::set($this->getKey($model),$model->toJson());
    }
    
    public function deleted($model){
        Redis::del($this->getKey($model));
        //Redis::srem($this->getCollectionKey($model),$model->id);
    }
    
    private function getKey(&$model)
    {
        return strtolower(class_basename($model)) . ":" . $model->id;
    }
    
    private function getCollectionKey(&$model)
    {
        return strtolower(str_plural(class_basename($model)));
    }
}