<?php

namespace App\Similar;

use App\Photo as BasePhoto;

class Photo extends BasePhoto
{
    //protected $visible = ['id'];
    
    public function similar($skip,$take)
    {
        return self::whereNull('deleted_at')->take(4)->skip($skip)
            ->take($take)
            ->get();
    }
}
