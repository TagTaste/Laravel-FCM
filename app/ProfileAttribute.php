<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfileAttribute extends Model
{
    use SoftDeletes;

    protected $storagePath = 'files/';

    protected $fillable = ['name','label','description','input_type','allowed_mime_types','enabled','required','user_id','parent_id','template_id','profile_type_id'];

    protected $casts = [
    	'multiline'=>'boolean',
    	'requires_upload'=>'boolean',
    	'enabled'=>'boolean',
    	'required'=>'boolean',
    ];

    protected $inputAttributes = [
        'textarea' => ['rows'=>10,'cols'=>30]
    ];

    public static function boot()
    {
        parent::boot();

        self::deleting(function($attribute){
            if($attribute->children->count()){
                $attribute->children->delete();
            }

            if($attribute->values->count()){
                $attribute->values->delete();
            }

            if($attribute->profile->count()){
                $attribute->profile->delete();
            }


        });
    }

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
        return $this->hasMany('\App\ProfileAttribute','parent_id');
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
        if(isset($this->inputAttributes[$this->input_type])){
            return $this->inputAttributes[$this->input_type];
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

    public function getFormInput($name=null,$profile=null,$inputValue = null,$inputAttributes=[])
    {
        if(is_null($this->input_type)){
            return \Form::bsLabel($this->label);

        }

        $component = "bs" . ucfirst($this->input_type);
        $valuesCount = $this->values->count();


        if(!$name){
            $name = "attributes[$this->id]";
        }

        if($valuesCount){

            echo \Form::bsLabel($this->label);

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


        return \Form::$component($this->label,$name,$inputValue,array_merge($inputAttributes,$this->getAttributesForInput()));
    }

    public static function getInputTypes() {
        return [
            'Short Text'=>'text',
            'Long Text' => 'textarea',
            'File Upload' => 'file',
            'Dropdown' => 'dropdown',
            'Dropdown with multiple select' => 'dropdown_multiple',
            'Multiple Options, Multiple Select'=>'checkbox',
            'Multiple Options, Single Select' => 'radio'];

    }

    public static function getTypeId($typeId)
    {
        $attribute = static::select('id')->where('profile_type_id','=',$typeId)->first();
        if(!$attribute){
            throw new \Exception("Could not get Attribute for Type: " . $typeId);
        }
        return $attribute;
    }

    public static function getChefAttributeId()
    {
        return static::getAttributeId('chef_id');
    }

    public static function getFoodieAttributeId()
    {
        return static::getAttributeId('foodie_id');
    }

    public static function getOutletAttributeId()
    {
        return static::getAttributeId('outlet_id');
    }

    public static function getIngredientsAttributeId()
    {
        return static::getAttributeId('ingredients_id');
    }

    public static function getExpertAttributeId()
    {
        return static::getAttributeId('expert_id');
    }

    public static function getAttributeId($name)
    {
        $attribute = static::select('id')->where('name','like',$name)->first();

        if(!$attribute){
            throw new \Exception("Attribute $attribute not found.");
        }
        return $attribute->id;
    }

}
