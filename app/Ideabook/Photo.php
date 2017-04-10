<?php

namespace App\Ideabook;

use App\Photo as BasePhoto;

class Photo extends BasePhoto
{
    protected $visible = ['id','caption','hasLiked','pivot','likeCount','photoUrl'];
    
    protected $appends = ['likeCount','photoUrl'];
}
