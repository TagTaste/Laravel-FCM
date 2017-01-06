<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TemplateType extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public static function boot()
    {
        parent::boot();

        //if these relationships exist, do not delete this model.
        // otherwise, there won't be any relationships to delete.
        // maybe stale articles.

        self::deleting(function($type){

            if($type->templates->count()){
                throw new \Exception("There are Templates using this Template Type. Aborting.");
                //$type->templates->delete();
            }
        });
    }

    public static function getAll(){
    	return static::select('id','name')->get()->pluck('id','name');
    }

    public function templates()
    {
        return $this->hasMany('\App\Templates');
    }
}
