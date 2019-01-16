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
        $data[] = ['product_category_id'=>116,'product_sub_category_id'=>257];
        $data[] = ['product_category_id'=>116,'product_sub_category_id'=>258];
        $data[] = ['product_category_id'=>116,'product_sub_category_id'=>259];
        $data[] = ['product_category_id'=>116,'product_sub_category_id'=>260];
        $data[] = ['product_category_id'=>116,'product_sub_category_id'=>261];

        $data[] = ['product_category_id'=>116,'product_sub_category_id'=>262];
        $data[] = ['product_category_id'=>116,'product_sub_category_id'=>263];
        $data[] = ['product_category_id'=>116,'product_sub_category_id'=>264];
        $data[] = ['product_category_id'=>116,'product_sub_category_id'=>265];
        $data[] = ['product_category_id'=>116,'product_sub_category_id'=>266];

        $data[] = ['product_category_id'=>117,'product_sub_category_id'=>267];
        $data[] = ['product_category_id'=>117,'product_sub_category_id'=>268];
        $data[] = ['product_category_id'=>117,'product_sub_category_id'=>269];

        $data[] = ['product_category_id'=>118,'product_sub_category_id'=>270];
        $data[] = ['product_category_id'=>118,'product_sub_category_id'=>271];

        $data[] = ['product_category_id'=>118,'product_sub_category_id'=>272];

        $data[] = ['product_category_id'=>118,'product_sub_category_id'=>273];

        $data[] = ['product_category_id'=>118,'product_sub_category_id'=>274];
        $data[] = ['product_category_id'=>118,'product_sub_category_id'=>275];
        $data[] = ['product_category_id'=>118,'product_sub_category_id'=>276];
        $data[] = ['product_category_id'=>118,'product_sub_category_id'=>277];
        $data[] = ['product_category_id'=>118,'product_sub_category_id'=>278];
        $data[] = ['product_category_id'=>118,'product_sub_category_id'=>279];
        $data[] = ['product_category_id'=>118,'product_sub_category_id'=>280];

        $data[] = ['product_category_id'=>119,'product_sub_category_id'=>281];
        $data[] = ['product_category_id'=>119,'product_sub_category_id'=>282];
        $data[] = ['product_category_id'=>119,'product_sub_category_id'=>283];
        $data[] = ['product_category_id'=>119,'product_sub_category_id'=>284];
        $data[] = ['product_category_id'=>120,'product_sub_category_id'=>285];

        $data[] = ['product_category_id'=>120,'product_sub_category_id'=>286];
        $data[] = ['product_category_id'=>120,'product_sub_category_id'=>287];
        $data[] = ['product_category_id'=>120,'product_sub_category_id'=>288];

        $data[] = ['product_category_id'=>120,'product_sub_category_id'=>289];
        $data[] = ['product_category_id'=>120,'product_sub_category_id'=>290];

        $data[] = ['product_category_id'=>120,'product_sub_category_id'=>291];

        $data[] = ['product_category_id'=>120,'product_sub_category_id'=>292];

        $data[] = ['product_category_id'=>120,'product_sub_category_id'=>293];
        $data[] = ['product_category_id'=>120,'product_sub_category_id'=>294];
        $data[] = ['product_category_id'=>120,'product_sub_category_id'=>295];
        $data[] = ['product_category_id'=>120,'product_sub_category_id'=>296];
        $data[] = ['product_category_id'=>120,'product_sub_category_id'=>297];
        $data[] = ['product_category_id'=>121,'product_sub_category_id'=>298];
        $data[] = ['product_category_id'=>121,'product_sub_category_id'=>299];

        $data[] = ['product_category_id'=>121,'product_sub_category_id'=>300];
        $data[] = ['product_category_id'=>121,'product_sub_category_id'=>301];
        $data[] = ['product_category_id'=>121,'product_sub_category_id'=>302];

        $data[] = ['product_category_id'=>121,'product_sub_category_id'=>303];
        $data[] = ['product_category_id'=>121,'product_sub_category_id'=>304];

        $data[] = ['product_category_id'=>122,'product_sub_category_id'=>305];

        $data[] = ['product_category_id'=>123,'product_sub_category_id'=>306];

        $data[] = ['product_category_id'=>123,'product_sub_category_id'=>307];
    $data[] = ['product_category_id'=>124,'product_sub_category_id'=>308];
        $data[] = ['product_category_id'=>125,'product_sub_category_id'=>309];
        $data[] = ['product_category_id'=>125,'product_sub_category_id'=>310];

        $data[] = ['product_category_id'=>126,'product_sub_category_id'=>311];
        $data[] = ['product_category_id'=>126,'product_sub_category_id'=>312];

        $data[] = ['product_category_id'=>126,'product_sub_category_id'=>313];

        $data[] = ['product_category_id'=>127,'product_sub_category_id'=>314];

        $data[] = ['product_category_id'=>127,'product_sub_category_id'=>315];
        $data[] = ['product_category_id'=>128,'product_sub_category_id'=>316];
        $data[] = ['product_category_id'=>129,'product_sub_category_id'=>317];

        $data[] = ['product_category_id'=>130,'product_sub_category_id'=>318];
        $data[] = ['product_category_id'=>130,'product_sub_category_id'=>319];

        $data[] = ['product_category_id'=>130,'product_sub_category_id'=>320];

        $data[] = ['product_category_id'=>131,'product_sub_category_id'=>321];

        $data[] = ['product_category_id'=>132,'product_sub_category_id'=>322];
        \DB::table("categories_pivot_sub_categories")->insert($data);
    }
}
