<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Privacy extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public static function boot()
    {
        parent::boot();

        self::deleting(function($privacy){

            //if these relationships exist, do not delete this model.
            // otherwise, there won't be any relationships to delete.
            // maybe stale articles.


            if($privacy->articles->count()){
                throw new \Exception("There are Articles using this Privacy Preference.");
                //$privacy->articles->delete();
            }

            if($privacy->ideabooks->count()){
                throw new \Exception("There are Ideabooks using this Privacy Preference.");
                //$privacy->ideabooks->delete();
            }
        });
    }

    public static function getAll(){
    	return static::select('id','name')->get()->pluck('name','id');
    }

    public static function defaultId()
    {
        return static::select('id')->where('name','like','public')->first()->id;
    }

    public function articles()
    {
        return $this->hasMany('\App\Article');
    }

    public function ideabooks()
    {
        return $this->hasMany('\App\Ideabook');
    }
}
