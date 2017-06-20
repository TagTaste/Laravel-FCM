<?php

namespace App\Similar;

use App\Ideabook as BaseModel;

class Ideabook extends BaseModel
{
    //protected $visible = ['id'];
    
    public function similar($skip,$take)
    {
        return self::select('ideabooks.id','ideabooks.name','users.name as username','profiles.id as profileId')
            ->join('users','users.id','=','ideabooks.user_id')
            ->join('profiles','profiles.user_id','=','users.id')
            ->where('profiles.id','!=',$this->profile_id)
            ->skip($skip)
            ->take($take)
            ->get();
    }
}
