<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfileAttribute extends Model
{

    protected $storagePath = 'files/';

    protected $fillable = ['name','label','description','multiline','requires_upload','allowed_mime_types','enabled','required','user_id','parent_id','template_id','profile_type_id'];

    protected $casts = [
    	'multiline'=>'boolean',
    	'requires_upload'=>'boolean',
    	'enabled'=>'boolean',
    	'required'=>'boolean',
    ];

    public function isRequired() {
    	return $this->required ? "required" : null;
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

    public function getMultilineAttribute($value) {
        return $this->booleanValue($value);
    }

    public function getRequiresUploadAttribute($value) {
        return $this->booleanValue($value);
    }

    public function getAllowedMimeTypesAttribute($value){
        return $value ?: "NA";
    }


    public function getEnabledAttribute($value){
        return $this->booleanValue($value);
    }

    public function getRequiredAttribute($value){
        return $this->booleanValue($value);
    }

    public function booleanValue(&$value){
        return $value ? "Yes" : "No";
    }

}
