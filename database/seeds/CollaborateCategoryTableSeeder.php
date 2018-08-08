<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class CollaborateCategoryTableSeeder extends Seeder {

    public function run()
    {
        \App\CollaborateCategory::insert([[
            'name' => 'Solid',
            'description' => 'Eg. Rusk, cookie, finger chips, etc.',
            'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_solid.png'
        ],[
            'name' => 'Semi - Solid',
            'description' => 'Eg. Peanut butter, jam, pickkle, etc.',
            'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_semi_solid.png'
        ],[
            'name' => 'Alcoholic Beverage',
            'description' => 'Eg. Whiskey, beer, vodka, gin, etc.',
            'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_alcoholic_beverage.png'
        ],[
            'name' => 'Non - Alcoholic Beverage',
            'description' => 'Eg. Coffee, milk, tea, soft drinks, etc.',
            'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_non_alcoholic_beverage.png'
        ]]);

    }

}