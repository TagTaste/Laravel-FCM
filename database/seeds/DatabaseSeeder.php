<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $this->call(BasicRoles::class);

        $this->call(DefaultAdminValues::class);
        //$this->call(DefaultRoleUserValues::class);
        $this->call(ProfileTypeTableSeeder::class);
    }
}
