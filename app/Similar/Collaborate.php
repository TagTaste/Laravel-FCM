<?php

namespace App\Similar;

use App\Collaborate as BaseModel;

class Collaborate extends BaseModel
{
    protected $fillable = [];
    
    protected $with = ['profile','company','fields'];

    protected $appends = ['interested','commentCount','likeCount'];

    public function similar()
    {
        return self::where('location','like',$this->location)->take(4)->get();
    }
}
