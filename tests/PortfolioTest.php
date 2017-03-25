<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PortfolioTest extends TestCase
{
    use DatabaseMigrations;
    
    private $profileId;
    private $companyId;
    private $headers;
    private $uri;
    
    protected function setUp()
    {
        parent::setUp();
        $user = factory(\App\User::class)->create();
        $token = JWTAuth::fromUser($user);
        $this->headers = ['HTTP_Authorization' => "Bearer " . $token];
    
        $profile = factory(\App\Profile::class)->create(['user_id'=>$user->id]);
        $company = factory(\App\Company::class)->create(['user_id'=>$user->id]);
        $this->profileId = $profile->id;
        $this->companyId = $company->id;
        $this->uri = "api/profiles/" . $this->profileId . "/companies/" . $this->companyId . "/portfolio";
    }
    
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndex()
    {
        $response = $this->json('get',$this->uri, [], $this->headers);
        $response->assertStatus(200);
    }
    
    public function testStore()
    {
        $data = ['worked_for'=>'company name','description'=>'work description', '_token' => csrf_token()];
        
        $response = $this->json('post',$this->uri, $data,$this->headers);
        $response->assertStatus(200);
    }
    
    public function testShow()
    {
        $data = ['worked_for'=>'company name','description'=>'work description','company_id'=>$this->companyId];
        $portfolio = \App\Company\Portfolio::create($data);
        $response = $this->json('get',$this->uri . "/" . $portfolio->id,[],$this->headers);
        $response->assertStatus(200);
    }
    
    public function testDelete()
    {
        $data = ['worked_for'=>'company name','description'=>'work description','company_id'=>$this->companyId];
        $portfolio = \App\Company\Portfolio::create($data);
        echo $portfolio->id;
        $response = $this->json('delete',$this->uri . "/" . $portfolio->id,['_token'=>csrf_field()],$this->headers);
        $response->assertStatus(200);
    }
    
    
}
