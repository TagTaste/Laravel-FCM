<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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

        return is_null($this->value) ? $this->attributeValue->name : $this->value;
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
}
