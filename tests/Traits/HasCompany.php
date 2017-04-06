<?php 
namespace Test\Traits;


trait HasCompany
{
    private $company;
    
    protected function bootHasCompany()
    {
        if(!$this->user){
            throw new \Exception("User not defined.");
        }
        
        $this->company = factory(\App\Company::class)->create(['user_id'=>$this->user->id]);
        $this->addUriPart("companies",$this->company->id);
    
    }
    
    protected function tearDownHasCompany()
    {
        if($this->company){
            $this->company->delete();
        }
    }
    
}