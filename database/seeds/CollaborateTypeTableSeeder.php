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
                'type_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_type_default.png'
            ],
            [
                'name' => 'Vegetarian - Jain',
                'type_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_type_default.png'
            ],
            [
                'name' => 'Vegan',
                'type_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_type_default.png'
            ]
            ,
            [
                'name' => 'Non Vegetarian',
                'type_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_type_default.png'
            ],
            [
                'name' => 'Contains Fish',
                'type_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_type_default.png'
            ],
            [
                'name' => 'Contains Pork',
                'type_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_type_default.png'
            ],
            [
                'name' => 'Contains Poultry',
                'type_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_type_default.png'
            ],
            [
                'name' => 'Halal Food',
                'type_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_type_default.png'
            ]

        ]);
    }
}
