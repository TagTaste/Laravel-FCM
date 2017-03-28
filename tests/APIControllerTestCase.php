<?php namespace Test;


class APIControllerTestCase extends APITestCase
{
    protected $data = [];
    protected $showDeleteUriParts = [];
    
    public function testIndex()
    {
        $response = $this->json('get',$this->getUri(), [], $this->headers);
        $response->assertStatus(200);
    }
    
    public function testStore()
    {
        $response = $this->json('post',$this->getUri($this->showDeleteUriParts), $this->data['create'],$this->headers);
        $response->assertStatus(200);
    }
    
    public function testShow()
    {
        $response = $this->json('get',$this->getUri($this->showDeleteUriParts),[],$this->headers);
        $response->assertStatus(200);
    }
    
    public function testDelete()
    {
        $response = $this->json('delete',$this->getUri($this->showDeleteUriParts),['_token'=>csrf_field()],$this->headers);
        $response->assertStatus(200);
    }
}