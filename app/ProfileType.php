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

            //if these relationships exist, do not delete this model.
            // otherwise, there won't be any relationships to delete.
            // maybe stale articles.

            if($type->products->count()){
                throw new \Exception("There are Products using this Profile Type. Aborting.");

                //$type->products->delete();
            }

            if($type->socialAccounts->count()){
                throw new \Exception("There are Social Accounts using this Profile Type. Aborting.");
                //$type->socialAccounts->delete();
            }

            if($type->articles->count()){
                throw new \Exception("There are Articles using this Profile Type. Aborting.");
                //$type->articles->delete();
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

    public function socialAccounts()
    {
        return  $this->hasMany('\App\SocialAccount');
    }

    public function articles()
    {
        return $this->hasMany('\App\Articles');
    }
}
