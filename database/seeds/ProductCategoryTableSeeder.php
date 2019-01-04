<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class ProductCategoryTableSeeder extends Seeder {

    public function run()
    {
        $data = [];
        $data[] = ['name'=>'Fast Food','is_active'=>1];
        $data[] = ['name'=>'Bakery','is_active'=>1];
        $data[] = ['name'=>'Confectionery','is_active'=>1];
        $data[] = ['name'=>'Beverages','is_active'=>1];
        $data[] = ['name'=>'Breakfast Cereal','is_active'=>1];
        $data[] = ['name'=>'Condiments','is_active'=>1];
        $data[] = ['name'=>'Dairy','is_active'=>1];
        $data[] = ['name'=>'Poultry Cereal','is_active'=>1];
        $data[] = ['name'=>'Tea','is_active'=>1];
        $data[] = ['name'=>'Processed Foods','is_active'=>1];

        \DB::table('product_categories')->insert($data);
    }

}