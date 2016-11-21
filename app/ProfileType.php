<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfileType extends Model
{
    protected $fillable = ['type','enabled','default'];

    protected $casts = ['enabled' => 'boolean', 'default' => 'boolean'];

    public function getEnabledAttribute($value){
    	 return $value ? "Yes" : "No";
    }

    public function getDefaultAttribute($value){
    	 return $value ? "Yes" : "No";
    }

    public static function getTypes() {
    	return static::select('id','type')->where('enabled',1)->orderBy('type','asc')->get()->pluck('type','id');
    }
}
