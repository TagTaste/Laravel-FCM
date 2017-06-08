<?php

namespace App\Shareable;

use App\Shareable\Share;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shoutout extends Share
{
    protected $fillable = ['profile_id','shoutout_id','payload_id'];
    protected $visible = ['id','profile_id','created_at'];
    
    public static function boot()
    {
        static::deleted(function($model){
            $model->payload->delete();
        });
    }
}
