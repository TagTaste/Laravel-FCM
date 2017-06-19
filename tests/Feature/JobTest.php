<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class JobTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        //middleware profile/1/companies/1/collaborate

        $this->get('/api/profiles/1/companies/1/jobs')
            ->assertSuccessful();

        $response = $this->json('POST', '/api/profiles/1/companies/1/jobs', ['title'=>'test','description'=>'developer',
            'location'=>'delhi','annual_salary'=>'100000',
            'functional_area'=>'job','key_skills'=>'coder','expected_role'=>'SDE','expires_on'=>'2017-06-30 00:00:00',
            'experience_required'=>'100 year',
            'profile_id'=>'1','company_id'=>'1','type_id'=>'1',
            'privacy_id'=>'1']);
        $response->assertStatus(200);

        $data=$response->send();
        $id=$data->getData()->data->job_id;

        $this->get('/api/profiles/1/companies/1/jobs/'.$id)
            ->assertSuccessful();

        $this->put('/api/profiles/1/companies/1/jobs/'.$id,['title'=>'test','description'=>'developer',
            'location'=>'delhi','annual_salary'=>'100000',
            'functional_area'=>'job','key_skills'=>'coder','expected_role'=>'SDE','expires_on'=>'2017-06-30 00:00:00',
            'experience_required'=>'100 year',
            'profile_id'=>'1','company_id'=>'1','type_id'=>'1',
            'privacy_id'=>'3'])
            ->assertSuccessful();

        $this->post('/api/profiles/1/companies/1/jobs/'.$id.'/apply')
            ->assertSuccessful();

        $this->post('/api/profiles/1/companies/1/jobs/'.$id.'/unapply')
            ->assertSuccessful();

        $this->get('/api/profiles/1/companies/1/jobs/'.$id.'/applications')
            ->assertSuccessful();

        $this->post('/api/profiles/1/companies/1/jobs/'.$id.'/applications/11/shortlist')
            ->assertSuccessful();

        $this->delete('/api/profiles/1/companies/1/jobs/'.$id)
            ->assertSuccessful();

        $this->get('/api/jobs/all')
            ->assertSuccessful();

        $this->get('/api/jobs/filters')
            ->assertSuccessful();

        $this->get('/api/jobs')
            ->assertSuccessful();

        $this->get('/api/jobs/1')
            ->assertSuccessful();


    }
}
