<?php

namespace App\V2;

use App\Channel;
use App\Job;
use App\Profile as BaseProfile;
use App\Shoutout;
use App\Subscriber;

class Profile extends BaseProfile
{
    protected $visible = ['id','user_id','name','designation','handle','tagline','image_meta','verified','is_tasting_expert','isFollowing','tasting_instructions','is_premium'];

    protected $appends = ['name','designation'];
    
    public function getDesignationAttribute()
    {
       return $this->professional !== null ? $this->professional->designation : null;
    }
}
