<?php

namespace App\Similar;

use App\Job as BaseJob;

class Job extends BaseJob
{
    //protected $visible = ['id'];
    
    public function similar($skip,$take)
    {
        return self::where('id','!=',$this->id)->whereNull('deleted_at')->skip($skip)
            ->take($take)
            ->get();
    }
}
