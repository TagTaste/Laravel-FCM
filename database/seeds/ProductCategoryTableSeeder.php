<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class ProductCategoryTableSeeder extends Seeder {

    public function run()
    {
        $data = [];
        $data[] = ['name'=>'Alcohol','is_active'=>1, 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-categories/Category+Images+/1.png'];
        $data[] = ['name'=>'Bakery','is_active'=>1, 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-categories/Category+Images+/2.png'];
        $data[] = ['name'=>'Beverage','is_active'=>1, 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-categories/Category+Images+/3.png'];
        $data[] = ['name'=>'Breakfast Cereals','is_active'=>1, 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-categories/Category+Images+/4.png'];
        $data[] = ['name'=>'Condiments (Relishes)','is_active'=>1, 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-categories/Category+Images+/5.png'];
        $data[] = ['name'=>'Dairy','is_active'=>1, 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-categories/Category+Images+/6.png'];
        $data[] = ['name'=>'Desserts','is_active'=>1, 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-categories/Category+Images+/7.png'];
        $data[] = ['name'=>'Grains','is_active'=>1, 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-categories/Category+Images+/8.png'];
        $data[] = ['name'=>'Meat','is_active'=>1, 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-categories/Category+Images+/9.png'];
        $data[] = ['name'=>'Poultry','is_active'=>1, 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-categories/Category+Images+/10.png'];
        $data[] = ['name'=>'Preserved Food','is_active'=>1, 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-categories/Category+Images+/11.png'];
        $data[] = ['name'=>'Raw','is_active'=>1, 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-categories/Category+Images+/12.png'];
        $data[] = ['name'=>'Savoury','is_active'=>1, 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-categories/Category+Images+/13.png'];
        $data[] = ['name'=>'Seafood','is_active'=>1, 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-categories/Category+Images+/14.png'];
        $data[] = ['name'=>'Tea','is_active'=>1, 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-categories/Category+Images+/15.png'];
        $data[] = ['name'=>'Legumes','is_active'=>1, 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-categories/Category+Images+/16.png'];
        $data[] = ['name'=>'Ready Meals','is_active'=>1, 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-categories/Category+Images+/17.png'];
        $data[] = ['name'=>'Confectionery','is_active'=>1, 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/product-categories/Category+Images+/18.png'];


        \DB::table('product_categories')->insert($data);
    }

}