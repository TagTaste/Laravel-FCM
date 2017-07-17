<?php


namespace App\Traits;


trait GetTags
{
    public function getTaggedProfiles($value)
    {
        if($this->has_tags === 0){
            return $value;
        }
        
        $found = preg_match_all('/@\[([0-9]*):([0-9]*)\]/i',$value,$matches);
        if($found === false){
            return $value;
        }
        
        $profiles = \App\Profile::getMultipleFromCache($matches[1]);
        if(!$profiles){
            return $value;
        }
        
        $value = [
            'text' => $value,
            'profiles' => $profiles
        ];
        return $value;
    }
}