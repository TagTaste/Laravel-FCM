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
                'name' => 'Non-vegetarian',
                'type_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_type_default.png'
            ],
            [
                'name' => 'Vaishno',
                'type_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_type_default.png'
            ]
            ,
            [
                'name' => 'Halal',
                'type_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_type_default.png'
            ],
            [
                'name' => 'Alcoholic',
                'type_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_type_default.png'
            ],
            [
                'name' => 'Kosher',
                'type_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_type_default.png'
            ],
            [
                'name' => 'Jain',
                'type_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_type_default.png'
            ],
            [
                'name' => 'Celiac',
                'type_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_type_default.png'
            ],
            [
                'name' => 'Vegan',
                'type_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_type_default.png'
            ],
            [
                'name' => 'Pareve',
                'type_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_type_default.png'
            ],
            [
                'name' => 'Buddhist',
                'type_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_type_default.png'
            ]

        ]);
    }
}
