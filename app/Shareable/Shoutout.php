<?php

namespace App\Shareable;

class Shoutout extends Share
{
   
    protected $with = ['shoutout'];
    
    public static function boot()
    {
        static::deleted(function($model){
            $model->payload->delete();
        });
    }
    
    public function shoutout()
    {
        return $this->belongsTo(\App\Shoutout::class,'shoutout_id');
    }

}
