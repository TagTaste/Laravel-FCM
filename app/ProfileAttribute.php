<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfileAttribute extends Model
{

    protected $storagePath = 'files/';

    protected $fillable = ['name','label','description','input_type','allowed_mime_types','enabled','required','user_id','parent_id','template_id','profile_type_id'];

    protected $casts = [
    	'multiline'=>'boolean',
    	'requires_upload'=>'boolean',
    	'enabled'=>'boolean',
    	'required'=>'boolean',
    ];

    protected $attributes = [
        'textarea' => ['rows'=>10,'cols'=>30]
    ];

    public function profileType() {
        return $this->belongsTo('\App\ProfileType','profile_type_id');
    }
    public function isRequired() {
    	return $this->required ? "required" : null;
    }

    public function requiresUpload() {
        return $this->requires_upload ? true : false;
    }

    public function children() {
        return $this->belongsTo('\App\ProfileAttribute','parent_id','id');
    }

    public static function getAll() {
        return static::orderBy('id', 'desc')->paginate(10);
    }

    public function parent() {
        return $this->belongsTo('\App\ProfileAttribute','parent_id');
    }

    public function getParent($attribute = null) {
        if($this->parent_id != 0 ) {
            if(!is_null($attribute)){
                return $this->parent->$attribute;
            }
            return $this->parent;
        }
    }

    public function values() {
        return $this->hasMany('\App\AttributeValue','attribute_id');
    }

    public function scopeType($query, $profileTypeId){
        return $query->where('profile_type_id','=',$profileTypeId);
    }

    public function inputType($type){
        return $this->input_type == $type;
    }

    public function getAttributesForInput()
    {
        if(isset($this->attributes[$this->input_type])){
            return $this->attributes[$this->input_type];
        }
        return [];
    }

    public function profile()
    {
        return $this->hasMany('\App\Profiles','profile_attribute_id');
    }

    public function getProfileValue($userId)
    {

    }

    public function getFormInput($name=null,$profile=null,$inputValue = null,$attributes=[])
    {
        $component = "bs" . ucfirst($this->input_type);
        if(!$name){
            $name = "attributes[$this->id]";
        }

        if($this->values->count() > 0){
            foreach($this->values as $attributeValue){

                $checked = false;
                $name = "attributes[{$this->id}][value_id][]";

                if($profile){
                    $profileValue = $profile->get($attributeValue->id);
                    if(isset($profileValue)){
                        $checked = true;
                        $name = "profile[{$profileValue->id}][value_id][]";
                    }
                }


                echo \Form::$component($attributeValue->name,$name,$attributeValue->id,compact('checked'));
            }
            return;
        }


        return \Form::$component($this->label,$name,$inputValue,array_merge($attributes,$this->getAttributesForInput()));
    }

}
