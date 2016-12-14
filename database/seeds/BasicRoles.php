<?php

use Illuminate\Database\Seeder;
use App\Role;

class BasicRoles extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::insert([[
            'name' => 'admin',
            'display_name' => 'Admin',
            'description' => 'Admin User',
        ],[
            'name' => 'deo',
            'display_name' => 'Data Entry Operator',
            'description' => 'DEO User',
        ],[
            'name' => 'foodie',
            'display_name' => 'Foodie',
            'description' => 'Foodie User',
        ]]);
    }
}
