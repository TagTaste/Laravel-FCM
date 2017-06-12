<?php

namespace App\Shareable;

use App\Shareable\Share;
use Illuminate\Database\Eloquent\SoftDeletes;

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
