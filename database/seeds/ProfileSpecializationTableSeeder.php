<?php

use Illuminate\Database\Seeder;

class ProfileSpecializationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];
        $data[] = ['name'=>'Chefs'];
        $data[] = ['name'=>'Researchers'];
        $data[] = ['name'=>'Food Technologist'];
        $data[] = ['name'=>'Beverage Experts'];
        $data[] = ['name'=>'Nutritionists'];
        $data[] = ['name'=>'Purchase Managers'];
        $data[] = ['name'=>'F&B Professionals'];
        $data[] = ['name'=>'Food Safety'];
        $data[] = ['name'=>'Farmers'];
        $data[] = ['name'=>'Flavorists'];
        $data[] = ['name'=>'Equipments'];
        $data[] = ['name'=>'Any Other'];

        \DB::table('profiles_specialization')->insert($data);

    }
}
