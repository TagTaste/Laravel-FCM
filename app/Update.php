<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Update extends Model
{
    protected $fillable = ['content', 'model_id', 'model_name', 'profile_id'];
    
    public static function boot()
    {
        static::created(function($model){
            \Redis::publish('notification-channel',$model->toJson());
        });
    }
}
