<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy

class JobTypeTableSeeder extends Seeder
{
    
    public function run()
    {
        \App\Job\Type::insert(
            [
                ['name' => 'Full Time'],
                ['name' => 'Part Time'],
                ['name' => 'Consultation']
            ]
        );
    }
    
}