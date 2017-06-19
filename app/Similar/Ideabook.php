<?php

namespace App\Similar;

use App\Ideabook as BaseModel;

class Ideabook extends BaseModel
{
    //protected $visible = ['id'];
    
    public function similar($skip,$take)
    {
        return self::select('ideabooks.id','name','profiles.id as profileId')
            ->join('profiles','profiles.user_id','=','ideabooks.user_id')
            ->where('profiles.id','!=',$this->profile_id)
            ->skip($skip)
            ->take($take)
            ->get();
    }
}
