<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RecipesTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        //middleware profile/1/recipe
        $this->get('/api/profiles/1/recipes')
            ->assertSuccessful();

        $response = $this->json('POST', '/api/profiles/1/recipes', ['description'=>'testing on phpunit','ingredients'=>'developer','image'=>'xyz.jpeg',
            'showcase'=>'1','hasRecipe'=>'1','category'=>'anyone','serving'=>'test',
            'calorie'=>'200','content'=>'youtube.com','name'=>'test',
            'preparation_time'=>'everytime','cooking_time'=>'morning','level'=>'1',
            'tags'=>'kuch bhi','tutorial_link'=>'tagtaste.com','billable'=>'100','privacy_id'=>'1']);
        $response->assertStatus(200);

        $data=$response->send();
        $id=$data->getData()->data->id;

        $this->get('/api/profiles/1/recipes/'.$id)
            ->assertSuccessful();

        $this->put('/api/profiles/1/recipes/'.$id,['description'=>'testing on phpunit','ingredients'=>'developer','image'=>'xyz.jpeg',
            'showcase'=>'1','hasRecipe'=>'1','category'=>'anyone','serving'=>'test',
            'calorie'=>'200','content'=>'youtube.com','name'=>'test',
            'preparation_time'=>'everytime','cooking_time'=>'morning','level'=>'1',
            'tags'=>'kuch bhi','tutorial_link'=>'tagtaste.com','billable'=>'100','privacy_id'=>'3'])
            ->assertSuccessful();

        $this->delete('/api/profiles/1/recipes/'.$id)
            ->assertSuccessful();

        //like recipe
        $this->post('/api/profiles/1/recipes/'.$id.'/like')
            ->assertSuccessful();

        //recipe

        $this->get('/api/recipes')
            ->assertSuccessful();

        $this->get('/api/recipes/2')
            ->assertSuccessful();

        $this->get('/api/recipes/image/2')
            ->assertSuccessful();

    }
}
