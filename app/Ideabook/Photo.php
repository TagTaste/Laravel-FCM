<?php

namespace App\Ideabook;

use App\Photo as BasePhoto;

class Photo extends BasePhoto
{
    protected $visible = ['id','caption','file','hasLiked','pivot','likeCount'];
    
    protected $appends = ['likeCount'];
}
