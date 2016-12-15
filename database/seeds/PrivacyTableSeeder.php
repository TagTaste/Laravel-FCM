<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;
use App\Privacy;
class PrivacyTableSeeder extends Seeder {

    public function run()
    {
        Privacy::insert([
            ['name'=>'Public'],
            ['name'=>'Network'],
            ['name'=>'Private'],
        ]);
    }

}