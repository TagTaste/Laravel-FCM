<?php

use Illuminate\Database\Seeder;
use App\User;

class DefaultAdminValues extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	User::create([
            'name' => 'Admin',
            'email' => 'admin@tagtaste.com',
            'password' => bcrypt('shadowqwerty'),
        ]);
    }
}
