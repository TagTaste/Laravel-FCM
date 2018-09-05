<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class CollaborateCategoryTableSeeder extends Seeder {

    public function run()
    {
        \App\CollaborateCategory::insert([
            [
                'name'=>'Baby Food',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Bakery',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Cereals',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Condiments',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Confectionery',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Dairy',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Desserts',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Eggs',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Fruits',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Fungi',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Grains',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Herbs',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Honey',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Legumes',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Meat',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Nuts',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Oil',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Poultry',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Preserves',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Savories',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Seafood',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Seeds',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Spices',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Sweetener',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Vegetable Pastes',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Vegetable Purees',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Vegetables',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Beer',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Beverages',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Cider',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Fruit Drinks',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Milk',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Non-alcoholic Beverages',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Spirits',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Water',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Whisky',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Wine',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Any Other',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ]
        ]);

    }

}
