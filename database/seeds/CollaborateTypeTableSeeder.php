<?php

use Illuminate\Database\Seeder;

class CollaborateTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('collaborate_types')->insert([

            [
                'name' => 'Vegetarian',
                'type_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_type_vegetarian.png'
            ],
            [
                'name' => 'Contain Egg',
                'type_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_type_contains_egg.png'
            ],
            [
                'name' => 'Non - Vegetarian',
                'type_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_type_non_vegetarian.png'
            ]

        ]);
    }
}
