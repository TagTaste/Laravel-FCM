<?php

namespace App\Application;

use App\Profile as BaseProfile;

class Profile extends BaseProfile
{
    protected $fillable = ['note'];
    
    //if you add a relation here, make sure you remove it from
    //App\Recommend to prevent any unwanted results like nested looping.
    protected $with = [];
    
    protected $visible = [
        'id',
        'tagline',
        'about',
        'imageUrl',
        'name',
        'address',
        'current',
        'handle'
    ];
    
    protected $appends = ['current', 'name', 'imageUrl'];
    
    //todo: if user is adding an experience as "currently working" make sure there are no other experiences marked as current.
    public function getCurrentAttribute()
    {
        $experience = $this->experience()->select('company', 'designation')->whereNull("end_date")->first();
        
        if (!$experience) {
            return;
        }
        return $experience;
    }
}
