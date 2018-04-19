<?php


namespace App\Traits;


trait HasRelated
{
    
    public $relatedKey = [];
    
    public function getRelatedKey() : array
    {
        if(empty($this->relatedKey) && $this->company_id !== null){
            return ['profile'=>'company:small:' . $this->company_id];
        }
        if(empty($this->relatedKey) && $this->profile_id !== null){
            return ['profile'=>'profile:small:' . $this->profile_id];
        }
        return [];
    }
}