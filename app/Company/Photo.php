<?php namespace App\Company;

use App\Photo as BasePhoto;

class Photo extends BasePhoto
{
    public function owner()
    {
        return $this->company->first();
    }
    
    public function getRelatedKey() : array
    {
        $owner = $this->owner();
        return ["company" => "company:small:" . $owner->id];
    }
    
}
