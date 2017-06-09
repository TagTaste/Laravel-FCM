<?php

namespace App\Shareable;

use App\Shareable\Share;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shoutout extends Share
{
   
    
    public static function boot()
    {
        static::deleted(function($model){
            $model->payload->delete();
        });
    }

}
