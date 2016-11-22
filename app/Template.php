<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
	public function type() {
		return $this->belongsTo('\App\TemplateType','template_type_id');
	}

    public static function for($type){
    	return static::select('id','name')->whereHas('type',function($query) use ($type) {
    		$query->where('name','like',$type);
    	})->get()->pluck('id','name');
    }
}
