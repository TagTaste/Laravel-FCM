<?php

namespace App\Similar;

use App\Profile as BaseProfile;

class Profile extends BaseProfile
{
    protected $with = [];
    
    protected $visible = ['id'];
    
    public function similar()
    {
        return self::take(4)->get();
    }
    
}
