<?php

use Illuminate\Database\Seeder;

class DefaultAdminValues extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    	DB::table('users')->truncate();
    	DB::table('users')->insert([
            'name' => 'Admin User',
            'email' => 'admin@tagtaste.com',
            'password' => '$2y$10$J0Vn9d63L1zTV2m6UMbItOEJkq3sh46caEmeRIvlCYXSfkbLzM3fa',
        ]);
    }
}
