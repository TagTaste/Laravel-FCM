<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TemplateType extends Model
{
    public static function getAll(){
    	return static::select('id','name')->get()->pluck('id','name');
    }
}
