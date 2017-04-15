<?php

namespace App\Similar;

use App\Photo as BasePhoto;

class Photo extends BasePhoto
{
    protected $visible = ['id'];
    
    public function similar()
    {
        return self::take(4)->get();
    }
}
