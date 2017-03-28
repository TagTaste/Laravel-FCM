<?php namespace Test\Traits;


trait HasProfile
{
    private $profile;
    
    protected function bootHasProfile()
    {
        if(!$this->user){
            throw new \Exception("User not defined.");
        }
        
        $this->profile = factory(\App\Profile::class)->create(['user_id'=>$this->user->id]);
        $this->addUriPart("profiles",$this->profile->id);
    }
    
    protected function tearDownHasProfile()
    {
        if($this->profile){
            $this->profile->delete();
        }
    }
    
}