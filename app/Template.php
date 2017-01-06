<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Template extends Model
{
    use SoftDeletes;

    public static function boot()
    {
        parent::boot();

        self::deleting(function($template){

            //if these relationships exist, do not delete this model.
            // otherwise, there won't be any relationships to delete.
            // maybe stale articles.

            if($template->articles->count()){
                throw new \Exception("There are articles using this Template.");
            }

            if($template->attributes->count()){
                throw new \Exception("There are Profile Attributes using this Template.");
            }


        });
    }

	public function type() {
		return $this->belongsTo('\App\TemplateType','template_type_id');
	}

    public static function forType($type){
    	return static::select('id','name')->whereHas('type',function($query) use ($type) {
    		$query->where('name','like',$type);
    	})->get()->pluck('id','name');
    }

    public function articles()
    {
        return $this->hasMany('\App\Article');
    }

    public function attributes()
    {
        return $this->hasMany('\App\ProfileAttribute');
    }
}
