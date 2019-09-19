<?php

namespace App\V2;

use App\Channel;
use App\Job;
use App\Company as BaseCompany;
use App\Shoutout;
use App\Subscriber;

class Company extends BaseCompany
{
    protected $visible = ['id','profile_id','name','logo_meta'];

    protected $appends = ['profile_id'];

    public function getProfileIdAttribute()
    {
        return $this->user->profile->id;
    }
    
}
