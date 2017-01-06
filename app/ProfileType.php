<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfileType extends Model
{
    use SoftDeletes;

    protected $fillable = ['type','enabled','default'];

    protected $casts = ['enabled' => 'boolean', 'default' => 'boolean'];

    protected $dates = ['deleted_at'];


    public static function boot()
    {
        parent::boot();

        ProfileType::deleting(function($type){
            if($type->products->count()){
                $type->products->delete();
            }
        });
    }

    public function getEnabledAttribute($value){
    	 return $value ? "Yes" : "No";
    }

    public function getDefaultAttribute($value){
    	 return $value ? "Yes" : "No";
    }

    public static function getTypes() {
    	return static::select('id','type')->where('enabled',1)->orderBy('type','asc')->get()->pluck('type','id');
    }

    public static function getTypeId($type)
    {
        $type = static::select('id')->where('enabled',1)->where('type','like',$type)->first();
        if(!$type){
            throw new \Exception("Type $type not found.");
        }

        return $type->id;
    }

    public function products()
    {
        return $this->hasMany('\App\Product');
    }
}
