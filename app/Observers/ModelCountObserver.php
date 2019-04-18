<?php namespace App\Observers;

use App\Events\DeleteFeedable;
use App\Events\NewFeedable;
use Illuminate\Support\Facades\Redis;

class ModelCountObserver {
    
    public function created($model)
    {
        Redis::hIncrBy("modelCounts",$this->getModelName($model),1);
    }
    
    public function deleted($model)
    {
        Redis::hIncrBy("modelCounts",$this->getModelName($model),-1);
    }
    
    private function getModelName(&$model)
    {
        $name = class_basename($model);
        return strtolower($name);
    }
}
