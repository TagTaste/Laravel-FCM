<?php
namespace App\Traits;

/**
 * Returns the cached key of the model.
 *
 * Class isCached
 * @package App\Traits
 */
use Illuminate\Support\Facades\Redis;
trait IsCached
{
    public function getCacheKey() : array
    {
        $name = strtolower(class_basename($this));
        $key = $name . ":" . $this->id;
        
        if(!Redis::exists($key))
        {
            Redis::set($key,$this->toJson());
        }
        return [$name => $key];
    }
}