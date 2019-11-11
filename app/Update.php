<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;

class Update extends Model
{
    protected $fillable = ['content', 'model_id', 'model_name', 'profile_id','is_read'];
    
    public static function boot()
    {
        static::created(function($model){
            $nameData=Profile::find($model->profile_id);
            $model['name']=$nameData->name;
            $model['profileImage']=$nameData->imageUrl;
            Redis::publish('notification-channel',$model->toJson());
        });
    }
}
