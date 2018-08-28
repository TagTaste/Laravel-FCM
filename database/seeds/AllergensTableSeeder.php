<?php

use Illuminate\Database\Seeder;

class AllergensTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];
        $data[] = ['name'=>'Milk'];
        $data[] = ['name'=>'Eggs'];
        $data[] = ['name'=>'Peanuts'];
        $data[] = ['name'=>'Tree Nuts'];
        $data[] = ['name'=>'Soy'];
        $data[] = ['name'=>'Wheat'];
        $data[] = ['name'=>'Fish'];
        $data[] = ['name'=>'Shellfish'];
        $data[] = ['name'=>'Corn'];
        $data[] = ['name'=>'Gelatin'];
        $data[] = ['name'=>'Meat'];
        $data[] = ['name'=>'Seeds'];
        $data[] = ['name'=>'Spices'];
        \DB::table('allergens')->insert($data);
    }
}
