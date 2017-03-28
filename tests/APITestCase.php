<?php namespace Test;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use \Tests\Traits\HasCompany;
use \Tests\Traits\HasProfile;

class APITestCase extends \TestCase
{
    protected $headers;
    
    private $baseUri = "api";
    private $uriParts = []; //lol
    protected $uri;
    
    
    protected $user;
    
    protected function setUp()
    {
        parent::setUp();
        $this->user = factory(\App\User::class)->create();
        
        //set auth headers
        $this->setAuthenticationHeaders();
        
        //boot own traits and then the child class traits.
        $this->bootTraits(self::class);
        $this->bootTraits(static::class);
    }
    
    protected function addUriPart()
    {
        $parts = func_get_args();
        $this->uriParts = array_merge($this->uriParts,$parts);
    }
    
    protected function getUri($parts = [])
    {
        
        return $this->baseUri . "/" . implode("/",$this->uriParts) . "/" . implode("/",$parts);
    }
    
    private function setAuthenticationHeaders()
    {
        $token = \JWTAuth::fromUser($this->user);
        $this->headers = ['HTTP_Authorization' => "Bearer " . $token];
    }
    
    private function bootTraits($class)
    {
        $traits = class_uses($class);
        if(count($traits) === 0){
            return;
        }
        
        foreach($traits as $trait){
            $bootMethod = "boot" . last(explode("\\",$trait));
            $this->$bootMethod();
        }
    }
    
    private function tearDownTraits($class)
    {
        $traits = class_uses($class);
        if(count($traits) === 0){
            return;
        }
        foreach($traits as $trait){
            $tearDownMethod = "tearDown" . last(explode("\\",$trait));
            $this->$tearDownMethod();
        }
    }
    
    protected function tearDown()
    {
        //tear down own traits and then child class traits
        $this->tearDownTraits(self::class);
        $this->tearDownTraits(static::class);
        
        $this->user->delete();
        parent::tearDown();
    }
}