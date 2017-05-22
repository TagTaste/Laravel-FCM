<?php namespace App\Profile;

use App\Photo as BasePhoto;

class Photo extends BasePhoto
{
    public function owner()
    {
        return $this->profile->first();
    }
    
    public function getRelatedKey() : array
    {
        
        $owner = $this->owner();
    
        return ["profile" => "profile:small:" . $owner->id];
    }
   
}
