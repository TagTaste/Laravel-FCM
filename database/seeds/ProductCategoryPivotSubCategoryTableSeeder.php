<?php

use Illuminate\Database\Seeder;

class ProductCategoryPivotSubCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];
        $data[] = ['product_category_id'=>1,'product_sub_category_id'=>1];
        $data[] = ['product_category_id'=>1,'product_sub_category_id'=>2];
        $data[] = ['product_category_id'=>1,'product_sub_category_id'=>3];
        $data[] = ['product_category_id'=>1,'product_sub_category_id'=>4];
        $data[] = ['product_category_id'=>1,'product_sub_category_id'=>5];

        $data[] = ['product_category_id'=>2,'product_sub_category_id'=>6];
        $data[] = ['product_category_id'=>2,'product_sub_category_id'=>7];
        $data[] = ['product_category_id'=>2,'product_sub_category_id'=>8];
        $data[] = ['product_category_id'=>2,'product_sub_category_id'=>2];
        $data[] = ['product_category_id'=>2,'product_sub_category_id'=>3];

        $data[] = ['product_category_id'=>3,'product_sub_category_id'=>9];
        $data[] = ['product_category_id'=>3,'product_sub_category_id'=>10];
        $data[] = ['product_category_id'=>3,'product_sub_category_id'=>11];

        $data[] = ['product_category_id'=>4,'product_sub_category_id'=>12];
        $data[] = ['product_category_id'=>4,'product_sub_category_id'=>13];

        $data[] = ['product_category_id'=>5,'product_sub_category_id'=>14];

        $data[] = ['product_category_id'=>6,'product_sub_category_id'=>15];

        $data[] = ['product_category_id'=>3,'product_sub_category_id'=>16];
        $data[] = ['product_category_id'=>6,'product_sub_category_id'=>17];

        \DB::table("categories_pivot_sub_categories")->insert($data);
    }
}
