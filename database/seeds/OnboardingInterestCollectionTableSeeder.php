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
        $data[] = ['name'=>'Chocolate','featured'=>1];
        $data[] = ['name'=>'Granola Bar','featured'=>0];
        $data[] = ['name'=>'Vegetable punch','featured'=>1];
        $data[] = ['name'=>'Green Salad','featured'=>1];
        $data[] = ['name'=>'Detox Juice','featured'=>1];
        $data[] = ['name'=>'Crunchy Sprouts salad','featured'=>0];
        $data[] = ['name'=>'Oatmeal','featured'=>1];
        $data[] = ['name'=>'Coffee','featured'=>1];
        $data[] = ['name'=>'Mushrooms','featured'=>1];
        $data[] = ['name'=>'Matcha','featured'=>1];
        $data[] = ['name'=>'Green Tea','featured'=>1];
        $data[] = ['name'=>'Butter Popcorn','featured'=>1];
        $data[] = ['name'=>'Potato chips','featured'=>1];
        $data[] = ['name'=>'Tacos','featured'=>0];
        $data[] = ['name'=>'Marmalade','featured'=>0];
        $data[] = ['name'=>'Ketchups','featured'=>1];
        $data[] = ['name'=>'French Toast','featured'=>0];
        $data[] = ['name'=>'Hummus','featured'=>1];
        $data[] = ['name'=>'Maple syrup','featured'=>1];
        $data[] = ['name'=>'Croissant','featured'=>1];
        $data[] = ['name'=>'Shish Kebab','featured'=>0];
        $data[] = ['name'=>'Pasta','featured'=>0];
        $data[] = ['name'=>'Donuts','featured'=>0];
        $data[] = ['name'=>'Corn','featured'=>1];
        $data[] = ['name'=>'Ice cream','featured'=>1];
        $data[] = ['name'=>'Hamburger','featured'=>0];
        $data[] = ['name'=>'Sushi','featured'=>0];
        $data[] = ['name'=>'Pizza','featured'=>0];
        $data[] = ['name'=>'Caramel Popcorns','featured'=>1];
        $data[] = ['name'=>'Waffles','featured'=>0];
        $data[] = ['name'=>'Masala Dosa','featured'=>0];
        $data[] = ['name'=>'Chilli Sauce','featured'=>1];
        $data[] = ['name'=>'Curd','featured'=>1];
        $data[] = ['name'=>'Tisanes','featured'=>1];
        $data[] = ['name'=>'Sandwich Bread','featured'=>1];
        $data[] = ['name'=>'Focaccia','featured'=>1];
        $data[] = ['name'=>'Noodles','featured'=>1];
        $data[] = ['name'=>'Noodles','featured'=>1];

        \DB::table('interested_collections')->insert($data);
    }
}
