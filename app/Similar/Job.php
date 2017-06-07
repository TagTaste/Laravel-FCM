<?php

namespace App\Similar;

use App\Job as BaseJob;

class Job extends BaseJob
{
    //protected $visible = ['id'];
    
    public function similar($skip,$take)
    {
        return self::where('location','like',$this->location)->skip($skip)
            ->take($take)
            ->get();
    }
}
