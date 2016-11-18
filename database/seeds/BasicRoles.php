<?php

use Illuminate\Database\Seeder;

class BasicRoles extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('roles')->truncate();
        DB::table('roles')->insert([
            'name' => 'admin',
            'display_name' => 'Admin',
            'description' => 'Admin User',
        ]);
        DB::table('roles')->insert([
            'name' => 'foodie',
            'display_name' => 'Foodie',
            'description' => 'Foodie User',
        ]);
    }
}
