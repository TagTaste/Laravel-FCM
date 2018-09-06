<?php

namespace App\Collaborate;

use App\Profile as BaseProfile;

class Profile extends BaseProfile
{
    protected $fillable = ['note'];
    
    //if you add a relation here, make sure you remove it from
    //App\Recommend to prevent any unwanted results like nested looping.
    protected $with = [];
    
    protected $visible = [
        'id','name'];
}
