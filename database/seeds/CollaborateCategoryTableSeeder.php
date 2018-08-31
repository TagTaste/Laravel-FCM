<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class CollaborateCategoryTableSeeder extends Seeder {

    public function run()
    {
        \App\CollaborateCategory::insert([
            [
                'name'=>'Alcoholic',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Bakery',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Bakery Ingredients',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Bakery Products',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Beverage',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Butter',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Cereals',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Cheese',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Chillies',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Chocolates',
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
                'name'=>'Cream',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Dairy',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Dry Fruits',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Edible Oils',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Eggs',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Flour',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Food',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Fruits',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Fruits State',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Gourmet',
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
                'name'=>'Ingredients',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Jams',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Juices',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Livestock Spices',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Logistics',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Meat Cuts',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Meats',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Meats Processed',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Milk Dairy',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Milk Non Dairy',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Millets Range',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Non Vegetarian',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Packing Type',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Peppers',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Processing',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Product Stage',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Rice',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Salts',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Sauce',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Seafood',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Seafood Raw',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Seasonings',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Shape',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Soyabeen',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Spices',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Spices States',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Starches',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'State',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Storage',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Syrup',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Vegetables',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Vineger',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ],
            [
                'name'=>'Yeastes',
                'category_image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-review-images/icons_category_default.png'
            ]
        ]);

    }

}