<?php

use Illuminate\Database\Seeder;

class OccupationsTableSeeder extends Seeder
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

        \DB::table('occupations')->insert($data);

    }
}
