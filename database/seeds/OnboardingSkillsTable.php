<?php

use Illuminate\Database\Seeder;

class OnboardingSkillsTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        \App\Onboarding::insert([
            ['key'=>'skills','value'=>'Entrepreneur'],
            ['key'=>'skills','value'=>'Pre-Opening'],
            ['key'=>'skills','value'=>'Management'],
            ['key'=>'skills','value'=>'Menu Development'],
            ['key'=>'skills','value'=>'Sales'],
            ['key'=>'skills','value'=>'Chef'],
            ['key'=>'skills','value'=>'Journalism'],
            ['key'=>'skills','value'=>'Export / Import'],
            ['key'=>'skills','value'=>'Packaging'],
            ['key'=>'skills','value'=>'Foodie'],
            ['key'=>'skills','value'=>'Blogging'],
            ['key'=>'skills','value'=>'Taster'],
            ['key'=>'skills','value'=>'Farming'],
            ['key'=>'skills','value'=>'Hospitality Management'],
            ['key'=>'skills','value'=>'Startups'],
            ['key'=>'skills','value'=>'Operations Management'],
            ['key'=>'skills','value'=>'Restaurant Management'],
            ['key'=>'skills','value'=>'Food Technology'],
            ['key'=>'skills','value'=>'FMCG'],
            ['key'=>'skills','value'=>'Marketing'],
            ['key'=>'skills','value'=>'Key Account Management'],
            ['key'=>'skills','value'=>'Food Technology'],
            ['key'=>'skills','value'=>'Culinary Science'],
            ['key'=>'skills','value'=>'Culinary Expert'],
            ['key'=>'skills','value'=>'Culinary Skills'],
            ['key'=>'skills','value'=>'Human Resource'],
            ['key'=>'skills','value'=>'Hotel Management'],
        ]);
    }
}
