<?php

use Illuminate\Database\Seeder;

class FoodieTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $data = [];
        $data[] = ['name'=>'Pescatarian','description'=>'Abstain from eating all meat and animal flesh with the exception of fish.'];
        $data[] = ['name'=>'Lacto - Ovo','description'=>'Abstain from eating Pork, Poultry, Fish, Shellfish, Beef or animal flesh of any kind, but do eat eggs and dairy products.'];
        $data[] = ['name'=>'Lacto Vegetarian','description'=>'Abstain from eating all kinds of meats, Poultry and Eggs but do eat dairy products.'];
        $data[] = ['name'=>'Vegans','description'=>'Do not eat meat of any kind and also not eggs, dairy products or processed foods containing these or animal derived ingredients.'];
        $data[] = ['name'=>'Raw Vegan / Raw Food Diet','description'=>'Consists of processed vegan foods that have not been heated above 115 F (46 C).'];
        $data[] = ['name'=>'Macrobiotic Diet','description'=>'Includes unprocessed vegan foods, such as whole grains, fruits, and vegetables and occasionally allows consumption of fish.'];
        $data[] = ['name'=>'Flexitarian','description'=>'Those who eat a vegetarian diet but occasionally eat meat.'];
        \DB::table('foodie_type')->insert($data);

    }
}
