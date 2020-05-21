<?php

namespace App\V1\Chat;

use App\Channel;
use App\Job;
use App\Profile as BaseProfile;
use App\Shoutout;
use App\Subscriber;

class Profile extends BaseProfile
{
    protected $fillable = [];

    protected $with = [];

    protected $visible = ['id','name','image','imageUrl','handle','image_meta','verified','is_tasting_expert'];

    protected $appends = ['name','imageUrl'];
    
    
}
