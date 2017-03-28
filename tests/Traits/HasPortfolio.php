<?php namespace Test\Traits;


trait HasPortfolio
{
    private $portfolio;
    
    protected function bootHasPortfolio()
    {
        if(!$this->user){
            throw new \Exception("User not defined.");
        }
        
        if(!$this->company){
            throw new \Exception("Company not defined.");
        }
        
        $this->portfolio = factory(\App\Company\Portfolio::class)->create(['company_id'=>$this->company->id]);
    }
    
    protected function tearDownHasPortfolio()
    {
        if($this->portfolio){
            $this->portfolio->delete();
        }
    }
    
}