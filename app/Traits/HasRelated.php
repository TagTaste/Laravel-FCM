<?php


namespace App\Traits;


trait HasRelated
{
    public function getRelatedKey() : array
    {
        if($this->profile_id !== null){
            return ['profile'=>'profile:small:' . $this->profile_id];
        }
        return [];
    }
}