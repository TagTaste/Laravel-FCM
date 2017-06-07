<?php

namespace App\Similar;

use App\Collaborate as BaseModel;

class Collaborate extends BaseModel
{
    protected $fillable = [];
    
    protected $with = ['profile','company','fields'];

    protected $appends = ['interested','commentCount','likeCount'];

    public function similar($skip,$take)
    {
        return self::where('location','like',$this->location)->skip($skip)
            ->take($take)
            ->get();
    }
}
