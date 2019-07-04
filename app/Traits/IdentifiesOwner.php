<?php


namespace App\Traits;


trait IdentifiesOwner
{
    /**
     * Returns Company Or Profile.
     *
     * If both company_id and profile_id is defined, preference if given to company.
     *
     * Eg. A new Occupation must go on the company's feed, but not necessarily on the feed of
     * the person creating the job.
     *
     * @return mixed
     */
    public function getOwner()
    {
        if($this->getAttributeValue('company_id') !== null){
            
            //if there's a custom implementation, call that.
            if(method_exists($this,'getCompany')){
                \Log::info("Calling custom company method.");
                return $this->getCompany();
            }
            
            return $this->company;
        } else if($this->getAttribute('profile_id') !== null){
            
            //there's a custom implementation, call that.
            if(method_exists($this,'getProfile')){
                \Log::info("Calling profile company method.");
    
                return $this->getProfile();
            }
            
            return $this->profile;
        } else {
            return null;
        }
        
        throw new \Exception("IdentifiesOwner Trait used, but this " . self::class . " belongs to neither Profile Nor Company");
    }
    
    public function owner()
    {
        return $this->getOwner();
    }
}