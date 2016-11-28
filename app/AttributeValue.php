<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{
    protected $fillable = ['attribute_id','name','value','default'];

    public function profileValue() {
    	//return $this->hasManyThrough('\App\AttributeValue','\App\Profile','id','value_id','value_id');
    	return $this->hasManyThrough('\App\AttributeValue','\App\Profile','id','value_id','value_id');
    }

    public function getValueForProfile($typeId, $userId){
    	return AttributeValue::select('id','name','value','profiles.value_id')
    			->leftJoin('profiles','profiles.value_id','=','attribute_values.id')
    			->where('profiles.user_id','=',$userId)
    			->where('profiles.type_id','=',$typeId)
    			->orWhereNull('profiles.value_id')->get();
    			
    }
}
