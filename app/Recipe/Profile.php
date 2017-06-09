<?php

namespace App\Recipe;

use App\Profile as BaseProfile;

class Profile extends BaseProfile
{
    protected $fillable = [];

    protected $with = [];

    protected $visible = ['id','name', 'designation','imageUrl','tagline','about','handle'];

    protected $appends = ['name','designation','imageUrl'];
    
    public function getDesignationAttribute()
    {
       return $this->professional !== null ? $this->professional->designation : null;
    }

}
