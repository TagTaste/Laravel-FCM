<?php

namespace App\Similar;

use App\Job as BaseJob;

class Job extends BaseJob
{
    //protected $visible = ['id'];
    
    public function similar()
    {
        return self::where('location','like',$this->location)->take(4)->get();
    }
}
