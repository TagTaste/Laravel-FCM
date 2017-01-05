<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Privacy extends Model
{
    public static function getAll(){
    	return static::select('id','name')->get()->pluck('name','id');
    }

    public static function defaultId()
    {
        return static::select('id')->where('name','like','public')->first()->id;
    }
}
