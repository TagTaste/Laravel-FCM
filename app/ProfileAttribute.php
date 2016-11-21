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

}
