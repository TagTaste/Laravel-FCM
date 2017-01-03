<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;
use App\TemplateType;

class TemplateTypeTableSeeder extends Seeder {

    public function run()
    {
        TemplateType::insert([
            ['name' => 'Dish'],
            ['name' => 'Recipe'],
            ['name' => 'Blog'],
        ]);
    }

}