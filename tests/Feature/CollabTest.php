<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CollabTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {

        //middleware profile/1/collaborate
        $this->get('/api/profiles/1/collaborate')
            ->assertSuccessful();

        $response = $this->json('POST', '/api/profiles/1/collaborate', ['title'=>'test','i_am'=>'developer','looking_for'=>'tester',
            'purpose'=>'job','deliverables'=>'yes','who_can_help'=>'anyone','expires_on'=>'2017-06-30 00:00:00',
            'keywords'=>'test','video'=>'youtube.com','location'=>'delhi',
            'profile_id'=>'1','company_id'=>'1','notify'=>'1',
            'privacy_id'=>'1']);
        $response->assertStatus(200);

        $data=$response->send();
        $id=$data->getData()->data->id;

        $this->get('/api/profiles/1/collaborate/'.$id)
            ->assertSuccessful();

        $this->put('/api/profiles/1/collaborate/'.$id,['title'=>'test','i_am'=>'developer','looking_for'=>'tester',
            'purpose'=>'job','deliverables'=>'yes','who_can_help'=>'anyone','expires_on'=>'2017-06-30 00:00:00',
            'keywords'=>'test','video'=>'youtube.com','location'=>'delhi',
            'profile_id'=>'1','company_id'=>'1','notify'=>'1',
            'privacy_id'=>'3'])
            ->assertSuccessful();

//        $this->delete('/api/profiles/1/collaborate/'.$id)
//            ->assertSuccessful();

        $this->post('/api/profiles/1/collaborate/'.$id.'/approve')
            ->assertSuccessful();

        $this->post('/api/profiles/1/collaborate/'.$id.'/reject')
            ->assertSuccessful();


        //middleware profile/1/companies/1/collaborate

        $this->get('/api/profiles/1/companies/1/collaborate')
            ->assertSuccessful();

        $response = $this->json('POST', '/api/profiles/1/companies/1/collaborate', ['title'=>'test','i_am'=>'developer','looking_for'=>'tester',
            'purpose'=>'job','deliverables'=>'yes','who_can_help'=>'anyone','expires_on'=>'2017-06-30 00:00:00',
            'keywords'=>'test','video'=>'youtube.com','location'=>'delhi',
            'profile_id'=>'1','company_id'=>'1','notify'=>'1',
            'privacy_id'=>'1']);
        $response->assertStatus(200);

        $data=$response->send();
        $id=$data->getData()->data->id;

        $this->get('/api/profiles/1/companies/1/collaborate/'.$id)
            ->assertSuccessful();
//
//        $this->put('/api/profiles/1/companies/1/collaborate/'.$id,['title'=>'test','i_am'=>'developer','looking_for'=>'tester',
//            'purpose'=>'job','deliverables'=>'yes','who_can_help'=>'anyone','expires_on'=>'2017-06-30 00:00:00',
//            'keywords'=>'test','video'=>'youtube.com','location'=>'delhi',
//            'profile_id'=>'1','company_id'=>'1','notify'=>'1',
//            'privacy_id'=>'3'])
//            ->assertSuccessful();
//
//        $this->delete('/api/profiles/1/companies/1/collaborate/'.$id)
//            ->assertSuccessful();

        $this->post('/api/profiles/1/companies/1/collaborate/'.$id.'/approve')
            ->assertSuccessful();

        $this->post('/api/profiles/1/companies/1/collaborate/'.$id.'/reject')
            ->assertSuccessful();

        //for shortlisted collaborate

        $this->get('/api/collaborate/shortlisted')
            ->assertSuccessful();

        $this->post('/api/collaborate/'.$id.'/shortlist')
            ->assertSuccessful();

        //collaborate

        $this->get('/api/collaborate/all')
            ->assertSuccessful();

        $this->get('/api/collaborate/filters')
            ->assertSuccessful();

        $this->post('/api/collaborate/'.$id.'/like')
            ->assertSuccessful();

        $this->post('/api/collaborate/'.$id.'/apply')
            ->assertSuccessful();

        $this->get('/api/collaborate')
            ->assertSuccessful();

        $res=$this->get('/api/collaborate/'.$id)
            ->assertSuccessful();

        $this->post('/api/collaborate/'.$id.'/fields',['collaboration_id'=>$id,'field_id'=>1])
            ->assertSuccessful();

//        $this->delete('/api/collaborate/'.$id.'/fields/1')
//            ->assertSuccessful();


    }
}
