<?php

namespace App\Similar;

use App\Ideabook as BaseModel;

class Ideabook extends BaseModel
{
    //protected $visible = ['id'];
    
    public function similar($skip,$take)
    {
        return self::select('ideabooks.id','ideabooks.name','ideabooks.user_id')
            ->where('ideabooks.user_id','!=',$this->user_id)->whereNull('deleted_at')
            ->skip($skip)
            ->take($take)
            ->get();
    }
}
