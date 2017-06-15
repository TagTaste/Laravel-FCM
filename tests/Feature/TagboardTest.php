<?php

namespace Tests\Feature;

use \Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TagboardTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->get('/api/profiles/1/tagboards')
            ->assertSuccessful();

        $response = $this->json('POST', '/api/profiles/1/tagboards', ['name'=>'ideabook','description'=>'testing','keywords'=>'test','privacy_id'=>3]);
        $response->assertStatus(200);

        $data=$response->send();
        $id=$data->getData()->data->id;

        $this->get('/api/profiles/1/tagboards/'.$id)
            ->assertSuccessful();

        $this->put('/api/profiles/1/tagboards/'.$id,['name'=>'ideabook','description'=>'testing','keywords'=>'test','privacy_id'=>1])
            ->assertSuccessful();

        $this->delete('/api/profiles/1/tagboards/'.$id)
            ->assertSuccessful();
    }
}
