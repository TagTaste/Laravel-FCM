<?php

use Illuminate\Database\Seeder;

class ProfileJobTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];
        $data[] = ['name'=>'Service'];
        $data[] = ['name'=>'Government'];
        $data[] = ['name'=>'Manufacturing'];
        $data[] = ['name'=>'Entrepreneurs'];
        $data[] = ['name'=>'Academia'];
        $data[] = ['name'=>'Students'];
        $data[] = ['name'=>'Home Makers'];
        $data[] = ['name'=>'Farmers'];
        $data[] = ['name'=>'Any Other'];

        \DB::table('profiles_job')->insert($data);

    }
}
