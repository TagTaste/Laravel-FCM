<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use App\ProfileAttribute;

class Profile extends Model
{
	protected $fillable = ['user_id','profile_attribute_id','value','value_id','type_id'];
	
    public function type() {
    	return $this->belongsTo('\App\ProfileType');
    }

    public function attribute() {
    	return $this->belongsTo('\App\ProfileAttribute','profile_attribute_id');
    }

    public function user() {
    	return $this->belongsTo('\App\User');
    }

    public function attributeValue() {
        return $this->belongsTo('\App\AttributeValue','value_id');
    }

    public function getValue() {
        if(is_null($this->value)){
            if($this->attributeValue){
                return $this->attributeValue->name;
            }
        }

        return $this->value;
    }


    public function scopeProfileType($query, $profileTypeId){
        return $query->where('type_id','=',$profileTypeId);
    }

    public function scopeForUser($query,$userId){
        return $query->where('user_id','=',$userId);
    }
    // public function getAttributeValue($attributeId){
    //     $self = static::where('profile_attribute_id',$attributeId)->first();
    //     if($self) return $self->getValue();
    //     return;
    // }

    public static function createProfileId($userId,$typeId)
    {
        $attribute = ProfileAttribute::getTypeid($typeId);
        Profile::firstOrCreate(['user_id'=>$userId,'profile_attribute_id'=>$attribute->id,'type_id'=>$typeId]);
        return;
    }

    public static function createDefaultProfile($userId)
    {
        $defaultProfileType = static::getDefaultProfile();
        $attribute = ProfileAttribute::getTypeid($defaultProfileType->id);
        Profile::firstOrCreate(['user_id'=>$userId,'profile_attribute_id'=>$attribute->id,'type_id'=>$defaultProfileType->id]);
        return;
    }

    public static function getDefaultProfile()
    {
        $default = ProfileType::where('default','=',1)->first();
        if(!$default){
            throw new \Exception("Could not find default profile type.");
        }

        return $default;
    }

    public function articles()
    {
        return $this->hasMany('\App\Article','author_id','id');
    }

}
