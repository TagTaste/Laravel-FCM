<?php

namespace Tests\Feature;

use Tests\TestCase;
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
    }

    public function tagboardGet()
    {
        $this->get('/api/profiles/1/tagboards')
            ->assertSuccessful();
    }
}
