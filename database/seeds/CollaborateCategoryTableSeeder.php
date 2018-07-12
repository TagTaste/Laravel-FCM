<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class CollaborateCategoryTableSeeder extends Seeder {

    public function run()
    {
        \App\CollaborateCategory::insert([[
            'name' => 'Solid',
            'description' => 'Eg. Rusk, cookie, finger chips, etc.'
        ],[
            'name' => 'Semi - Solid',
            'description' => 'Eg. Peanut butter, jam, pickkle, etc.'
        ],[
            'name' => 'Alcoholic Beverage',
            'description' => 'Eg. Whiskey, beer, vodka, gin, etc.'
        ],[
            'name' => 'Non - Alcoholic Beverage',
            'description' => 'Eg. Coffee, milk, tea, soft drinks, etc.'
        ]]);

    }

}