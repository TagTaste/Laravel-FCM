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

}
