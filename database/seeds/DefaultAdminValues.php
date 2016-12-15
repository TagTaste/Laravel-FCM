<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;

class DefaultAdminValues extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$user = User::create([
            'name' => 'Admin',
            'email' => 'admin@tagtaste.com',
            'password' => bcrypt('shadowqwerty'),
        ]);

    	$admin = Role::where('name','like','admin')->first();

    	if(!$admin){
    	    throw new \Exception("Could not find Admin role.");
        }

        $user->attachRole($admin);

    }
}
