<?php


namespace App\Traits;


trait GetTags
{
    
    private function getMatches($data,$pattern = '/@\[([0-9]*):([0-9]*)\]/i'){
        $matches = [];
        return preg_match_all($pattern,$data,$matches) ? $matches : false;
    }
    
    public function getTaggedProfiles($value)
    {
        if(isset($this->has_tags) && $this->has_tags === 0){
            return false;
        }
        
        $matches = $this->getMatches($value);
        if($matches === false){
            return false;
        }
        
        $profiles = \App\Profile::getMultipleFromCache($matches[1]);

        return $profiles === false ? false : $profiles;
    }

    public function getTaggedProfilesV2($value)
    {
        if(isset($this->has_tags) && $this->has_tags === 0){
            return false;
        }
        
        $matches = $this->getMatches($value);
        if($matches === false){
            return false;
        }
        
        $profiles = \App\Profile::getMultipleFromCacheV2($matches[1]);

        return $profiles === false ? false : $profiles;
    }
}