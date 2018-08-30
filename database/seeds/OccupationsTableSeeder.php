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
        $data[] = ['name'=>'Entrepreneur'];
        $data[] = ['name'=>'Academia'];
        $data[] = ['name'=>'Student'];
        $data[] = ['name'=>'Home Maker'];
        $data[] = ['name'=>'Farmer'];
        $data[] = ['name'=>'Any Other'];

        \DB::table('occupations')->insert($data);

    }
}
