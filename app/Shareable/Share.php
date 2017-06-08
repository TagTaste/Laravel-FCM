<?php

namespace App\Shareable;

use App\Channel\Payload;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Share extends Model
{
    use SoftDeletes;
    
    public function __construct($attributes = [])
    {
        $class = strtolower(class_basename($this));
        $this->table = $class . "_shares";
        parent::__construct($attributes);
    }
    
    public static function boot()
    {
        static::deleted(function($model){
            \Log::info("hello");
            $model->payload->delete();
        });
    
        self::deleted(function($model){
            \Log::info("foobar");
            $model->payload->delete();
    
        });
    }
    
    public function payload()
    {
        return $this->belongsTo(Payload::class,'payload_id');
    }
    
    public function getColumnName()
    {
        return strtolower(class_basename($this)) . "_id";
    }
    
    public static function getSharedAt($modelName,$id)
    {
        $columnName = strtolower($modelName) . "_id";
        $shareable = "\\App\\Shareable\\" . $modelName;
        $model = $shareable::where($columnName,$id)->first();
        return $model !== null && $model->payload !== null ? $model->payload->shared_at : null;
    }
}
