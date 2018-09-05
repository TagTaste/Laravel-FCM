<?php

use Illuminate\Database\Seeder;

class SpecializationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];
        $data[] = ['name'=>'Chef'];
        $data[] = ['name'=>'Researcher'];
        $data[] = ['name'=>'Food Technologist'];
        $data[] = ['name'=>'Beverage Expert'];
        $data[] = ['name'=>'Nutritionist'];
        $data[] = ['name'=>'Purchase Manager'];
        $data[] = ['name'=>'F&B Professional'];
        $data[] = ['name'=>'Food Safety'];
        $data[] = ['name'=>'Farmer'];
        $data[] = ['name'=>'Flavorist'];
        $data[] = ['name'=>'Equipment'];
        $data[] = ['name'=>'Any Other'];

        \DB::table('specializations')->insert($data);

    }
}
