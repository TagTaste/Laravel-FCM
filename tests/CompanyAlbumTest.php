<?php 

namespace Test;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Test\Traits\HasProfile;
use Test\Traits\HasCompany;
use Test\Traits\HasAlbum;

class CompanyAlbumTest extends APIControllerTestCase
{
    //use DatabaseMigrations;
    use HasProfile,HasCompany,HasAlbum;
    
    protected function setUp()
    {
        parent::setUp();
        $this->data['create'] = ['name'=>'album name','description'=>'album description', '_token' => csrf_token()];
        //$this->showDeleteUriParts = ["portfolio",$this->portfolio->id];
        $this->addUriPart("albums");
    }
    
    public function testDelete()
    {
        $this->addUriPart($this->album->id);
        parent::testDelete();
        
    }
    
    
}
