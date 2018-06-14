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
        $collaborate = self::skip($skip)->take($take);
        if($this->location){
            $collaborate = $collaborate->where("location",'like',$this->location);
        }
        return $collaborate->whereNull('deleted_at')->where('id','!=',$this->id)->get();
    }
}
