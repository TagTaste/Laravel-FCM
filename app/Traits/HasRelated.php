<?php


namespace App\Traits;


trait HasRelated
{
    
    public $relatedKey = [];
    
    public function getRelatedKey() : array
    {
        if(empty($this->relatedKey) && $this->profile_id !== null){
            return ['profile'=>'profile:small:' . $this->profile_id];
        }
        return [];
    }
}