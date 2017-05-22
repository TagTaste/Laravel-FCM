<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use \Redis as Cache;
use \DB;

/**
 * Caches a given Model.
 * Would re-fetch the model using the query builder, to avoid the $with[] and $appends[].
 *
 * Unaware of better methods.
 *
 * Sets key as $prefix:model:$suffix:id
 * Class Cacheable
 * @package App
 */

class Cacheable
{
    public $key;
    public $table;
    public $suffix;
    public $prefix;
    public $data;
    public $model;
    
    /**
     * @var Redis
     */
    private $cache;
    
    private function __construct(Model $model, $suffix = null, $prefix = null)
    {
        $this->model = $model;
        $this->boot();
    }
    
    private static function getInstance($model) : Cacheable
    {
        return new self($model);
    }
    
    private function boot() : void
    {
        $this->setKey();
        $this->setTable();
        $this->setData();
    }
    
    private function setKey() : void
    {
        $this->key = strtolower(class_basename($this->model)) . ":" . $this->model->id;
    }
    
    private function setTable() : void
    {
        $this->table = $this->model->getTable();
    }
    
    private function setData() : void
    {
        $this->data = Db::table($this->table)->find($this->model->id);
    }
    
    /**
     * Store in Cache as String
     *
     * @param Model $model
     * @return bool
     */
    public static function set(Model $model)
    {
        return self::getInstance($model)->setString();
    }
    
    public function setString()
    {
        $status = Cache::set($this->key,json_encode($this->data));
    
        if(!$status) return $status;
    
        return $this;
    }
    
    public static function del(Model $model)
    {
        return self::getInstance($model)->delString();
    }
    
    private function delString()
    {
        $status = Cache::del($this->key,json_encode($this->data));
        
        if(!$status) return $status;
        
        return $this;
    }
    
    public static function sadd(Model $model, $models = "")
    {
        return self::getInstance($model)->setAdd($models);
    }
    
    private function setAdd($models)
    {
        $status = Cache::sAdd($models,$this->model->id);
        
        if(!$status) return $status;
    
        return $this;
    }
    
    public static function srem(Model $model)
    {
        return self::getInstance($model)->setRemove($model);
    }
    
    private function setRemove($models)
    {
        $status = Cache::sRemove($models,$this->model->id);
    
        if(!$status) return $status;
    
        return $this;
    }
   
    /**
     * Pass call to redis if methods are not defined here.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        return Redis::$method(...$parameters);
    }
}