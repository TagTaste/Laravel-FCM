<?php


namespace App\Traits;


trait IdentifiesOwner
{
    /**
     * Returns Company Or Profile.
     *
     * If both company_id and profile_id is defined, preference if given to company.
     *
     * Eg. A new Job must go on the company's feed, but not necessarily on the feed of
     * the person creating the job.
     *
     * @return mixed
     */
    public function getOwner()
    {
        if(property_exists($this,'company_id')){
            if($this->company_id !== null){
                return $this->getCompany();
            }
        }
        
        if(property_exists($this,'profile_id')){
            if($this->profile_id !== null){
                return $this->getProfile();
            }
        }
        
        throw new \Exception("IdentifiesOwner Trait used, but this " . self::class . " belongs to neither Profile Nor Company");
    }
}