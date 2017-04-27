<?php

namespace App\Similar;

use App\Profile as BaseProfile;

class Profile extends BaseProfile
{
    protected $with = [];
    
    protected $visible = ['id','name','image','tagline'];
    
    public function similar()
    {
        return self::join('subscribers','subscribers.id','=','profiles.id')
            ->where('subscribers.channel_name','not like','network.' . $this->id)
            ->where('subscribers.channel_name','not like','public.' . $this->id)
            ->paginate(5);
    }
    
}
