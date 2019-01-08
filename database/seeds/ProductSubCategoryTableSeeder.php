<?php

use Illuminate\Database\Seeder;

class ProductSubCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];
        $data[] = ["name"=>"Pizza","is_active"=>1];
        $data[] = ["name"=>"Burger","is_active"=>1];
        $data[] = ["name"=>"Fries","is_active"=>1];
        $data[] = ["name"=>"Fast Food","is_active"=>1];

        $data[] = ["name"=>"Bakery","is_active"=>1];

        $data[] = ["name"=>"Potato Wafers","is_active"=>1];
        $data[] = ["name"=>"Candies","is_active"=>1];
        $data[] = ["name"=>"Chocolates","is_active"=>1];
        $data[] = ["name"=>"Cakes","is_active"=>1];

        $data[] = ["name"=>"Soft Drinks","is_active"=>1];
        $data[] = ["name"=>"Coffee","is_active"=>1];
        $data[] = ["name"=>"Juices","is_active"=>1];

        $data[] = ["name"=>"Cereals‎","is_active"=>1];
        $data[] = ["name"=>"Oats","is_active"=>1];

        $data[] = ["name"=>"Dips & Dressing","is_active"=>1];
        $data[] = ["name"=>"Spreads","is_active"=>1];
        $data[] = ["name"=>"Jams","is_active"=>1];

        $data[] = ["name"=>"A2 milk","is_active"=>1];
        $data[] = ["name"=>"Galalith","is_active"=>1];
        $data[] = ["name"=>"A2 milk","is_active"=>1];
        $data[] = ["name"=>"Galalith","is_active"=>1];

        $data[] = ["name"=>"Butter","is_active"=>1];
        $data[] = ["name"=>"Cream","is_active"=>1];
        $data[] = ["name"=>"Yogurt","is_active"=>1];

        $data[] = ["name"=>"Chicken","is_active"=>1];
        $data[] = ["name"=>"Eggs","is_active"=>1];

        $data[] = ["name"=>"Green Tea","is_active"=>1];
        $data[] = ["name"=>"CTC","is_active"=>1];
        $data[] = ["name"=>"Orthodox","is_active"=>1];

        $data[] = ["name"=>"Pasta","is_active"=>1];
        $data[] = ["name"=>"Noodles","is_active"=>1];

        \DB::table("product_sub_categories")->insert($data);
    }
}
