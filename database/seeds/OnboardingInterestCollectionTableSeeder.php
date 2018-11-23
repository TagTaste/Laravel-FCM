<?php

use Illuminate\Database\Seeder;

class OnboardingInterestCollectionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];
        $data[] = ['name'=>'Beverage','featured'=>1];
        $data[] = ['name'=>'Food','featured'=>1];
        $data[] = ['name'=>'Fruits','featured'=>1];
        $data[] = ['name'=>'Vegetables','featured'=>1];
        $data[] = ['name'=>'Protein','featured'=>1];
        $data[] = ['name'=>'Dairy','featured'=>1];
        $data[] = ['name'=>'Any Other','featured'=>1];

        \DB::table('interested_collections')->insert($data);
    }
}
