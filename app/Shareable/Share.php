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
    }
    
    public function payload()
    {
        return $this->belongsTo(Payload::class,'payload_id');
    }
    
    public function getColumnName()
    {
        return strtolower(class_basename($this)) . "_id";
    }
    
    public static function getSharedAt(Model $model)
    {
        $columnName = strtolower(class_basename($model)) . "_id";
        $shareable = "\\App\\Shareable\\" . class_basename($model);
        $model = $shareable::where($columnName,$model->id)->first();
        return $model !== null && $model->payload !== null ? $model->payload->created_at->toDateTimeString() : null;
    }
}
