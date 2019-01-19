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

        $data[] = ['product_category_id'=>1,'product_sub_category_id'=>6];
        $data[] = ['product_category_id'=>1,'product_sub_category_id'=>7];
        $data[] = ['product_category_id'=>1,'product_sub_category_id'=>8];
        $data[] = ['product_category_id'=>1,'product_sub_category_id'=>9];
        $data[] = ['product_category_id'=>1,'product_sub_category_id'=>10];

        $data[] = ['product_category_id'=>2,'product_sub_category_id'=>11];
        $data[] = ['product_category_id'=>2,'product_sub_category_id'=>12];
        $data[] = ['product_category_id'=>2,'product_sub_category_id'=>13];

        $data[] = ['product_category_id'=>3,'product_sub_category_id'=>14];
        $data[] = ['product_category_id'=>3,'product_sub_category_id'=>15];

        $data[] = ['product_category_id'=>3,'product_sub_category_id'=>16];

        $data[] = ['product_category_id'=>3,'product_sub_category_id'=>17];

        $data[] = ['product_category_id'=>3,'product_sub_category_id'=>18];
        $data[] = ['product_category_id'=>3,'product_sub_category_id'=>19];
        $data[] = ['product_category_id'=>3,'product_sub_category_id'=>20];
        $data[] = ['product_category_id'=>3,'product_sub_category_id'=>21];
        $data[] = ['product_category_id'=>3,'product_sub_category_id'=>22];
        $data[] = ['product_category_id'=>3,'product_sub_category_id'=>23];
        $data[] = ['product_category_id'=>3,'product_sub_category_id'=>24];

        $data[] = ['product_category_id'=>4,'product_sub_category_id'=>25];
        $data[] = ['product_category_id'=>4,'product_sub_category_id'=>26];
        $data[] = ['product_category_id'=>4,'product_sub_category_id'=>27];
        $data[] = ['product_category_id'=>4,'product_sub_category_id'=>28];
        $data[] = ['product_category_id'=>5,'product_sub_category_id'=>29];

        $data[] = ['product_category_id'=>5,'product_sub_category_id'=>30];
        $data[] = ['product_category_id'=>5,'product_sub_category_id'=>31];
        $data[] = ['product_category_id'=>5,'product_sub_category_id'=>32];

        $data[] = ['product_category_id'=>5,'product_sub_category_id'=>33];
        $data[] = ['product_category_id'=>5,'product_sub_category_id'=>34];

        $data[] = ['product_category_id'=>5,'product_sub_category_id'=>35];

        $data[] = ['product_category_id'=>5,'product_sub_category_id'=>36];

        $data[] = ['product_category_id'=>5,'product_sub_category_id'=>37];
        $data[] = ['product_category_id'=>5,'product_sub_category_id'=>38];
        $data[] = ['product_category_id'=>5,'product_sub_category_id'=>39];
        $data[] = ['product_category_id'=>5,'product_sub_category_id'=>40];
        $data[] = ['product_category_id'=>5,'product_sub_category_id'=>41];
        $data[] = ['product_category_id'=>6,'product_sub_category_id'=>42];
        $data[] = ['product_category_id'=>6,'product_sub_category_id'=>43];

        $data[] = ['product_category_id'=>6,'product_sub_category_id'=>44];
        $data[] = ['product_category_id'=>6,'product_sub_category_id'=>45];
        $data[] = ['product_category_id'=>6,'product_sub_category_id'=>46];

        $data[] = ['product_category_id'=>6,'product_sub_category_id'=>47];
        $data[] = ['product_category_id'=>6,'product_sub_category_id'=>48];

        $data[] = ['product_category_id'=>7,'product_sub_category_id'=>49];

        $data[] = ['product_category_id'=>8,'product_sub_category_id'=>50];

        $data[] = ['product_category_id'=>8,'product_sub_category_id'=>51];
    $data[] = ['product_category_id'=>9,'product_sub_category_id'=>52];
        $data[] = ['product_category_id'=>10,'product_sub_category_id'=>53];
        $data[] = ['product_category_id'=>10,'product_sub_category_id'=>54];

        $data[] = ['product_category_id'=>11,'product_sub_category_id'=>55];
        $data[] = ['product_category_id'=>11,'product_sub_category_id'=>56];

        $data[] = ['product_category_id'=>11,'product_sub_category_id'=>57];

        $data[] = ['product_category_id'=>12,'product_sub_category_id'=>58];

        $data[] = ['product_category_id'=>12,'product_sub_category_id'=>59];
        $data[] = ['product_category_id'=>13,'product_sub_category_id'=>60];
        $data[] = ['product_category_id'=>14,'product_sub_category_id'=>61];

        $data[] = ['product_category_id'=>15,'product_sub_category_id'=>62];
        $data[] = ['product_category_id'=>15,'product_sub_category_id'=>63];

        $data[] = ['product_category_id'=>15,'product_sub_category_id'=>64];

        $data[] = ['product_category_id'=>16,'product_sub_category_id'=>65];

        $data[] = ['product_category_id'=>17,'product_sub_category_id'=>66];
        \DB::table("categories_pivot_sub_categories")->insert($data);
    }
}
