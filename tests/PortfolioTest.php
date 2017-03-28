<?php namespace Test;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Test\Traits\HasProfile;
use Test\Traits\HasCompany;
use Test\Traits\HasPortfolio;

class PortfolioTest extends APIControllerTestCase
{
    //use DatabaseMigrations;
    use HasProfile, HasCompany, HasPortfolio;
    
    protected function setUp()
    {
        parent::setUp();
        $this->data['create'] = ['worked_for'=>'company name','description'=>'work description', '_token' => csrf_token()];
        //$this->showDeleteUriParts = ["portfolio",$this->portfolio->id];
        $this->addUriPart("portfolio");
    }
    
    public function testDelete()
    {
        $this->addUriPart($this->portfolio->id);
        parent::testDelete();
        
    }
    
    
}
