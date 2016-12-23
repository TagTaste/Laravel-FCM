<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;
use App\ProfileType;

class ProfileTypeTableSeeder extends Seeder {

    public function run()
    {
        ProfileType::insert(
        	[['type' => 'Chef', 'enabled' => 1, 'default' => 0],
        	['type' => 'Foodie', 'enabled' => 1, 'default' => 1],
        	['type' => 'Outlet', 'enabled' => 1, 'default' => 0],
        	['type' => 'Supplier', 'enabled' => 1, 'default' => 0],
        	['type' => 'Expert', 'enabled' => 1, 'default' => 0]]);
    }

}