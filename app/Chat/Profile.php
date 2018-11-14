<?php

namespace App\Chat;

use App\Channel;
use App\Job;
use App\Profile as BaseProfile;
use App\Shoutout;
use App\Subscriber;

class Profile extends BaseProfile
{
    protected $fillable = [];

    protected $with = [];

    protected $visible = ['id','name','image'];

    protected $appends = ['name'];
    
    
}
