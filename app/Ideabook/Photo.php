<?php

namespace App\Ideabook;

use App\Photo as BasePhoto;

class Photo extends BasePhoto
{
    protected $visible = ['id','caption','hasLiked','pivot','likeCount','photoUrl','profile_id'];
    
    protected $appends = ['likeCount','photoUrl'];
    
    public function getProfileIdAttribute()
    {
        return $this->getProfile()->id;
    }
}
