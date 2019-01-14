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
        $data[] = ["name"=>"Cocktails","is_active"=>1];
        $data[] = ["name"=>"Beer","is_active"=>1];
        $data[] = ["name"=>"Wine","is_active"=>1];
        $data[] = ["name"=>"Rum","is_active"=>1];
        $data[] = ["name"=>"Whiskey","is_active"=>1];
        $data[] = ["name"=>"Brandy","is_active"=>1];
        $data[] = ["name"=>"Gin","is_active"=>1];
        $data[] = ["name"=>"Vodka","is_active"=>1];
        $data[] = ["name"=>"Tequila","is_active"=>1];
        $data[] = ["name"=>"Scotch","is_active"=>1];
        $data[] = ["name"=>"Bread","is_active"=>1];
        $data[] = ["name"=>"Salty Food","is_active"=>1];
        $data[] = ["name"=>"Soft Drinks","is_active"=>1];
        $data[] = ["name"=>"Energy Drinks","is_active"=>1];
        $data[] = ["name"=>"Juices","is_active"=>1];
        $data[] = ["name"=>"Malt Drinks","is_active"=>1];
        $data[] = ["name"=>"Milk Based Drinks","is_active"=>1];
        $data[] = ["name"=>"Mineral Water","is_active"=>1];
        $data[] = ["name"=>"Instant Drink Mixes","is_active"=>1];
        $data[] = ["name"=>"Functional Beverage'","is_active"=>1];
        $data[] = ["name"=>"Concentrates","is_active"=>1];
        $data[] = ["name"=>"Mocktails","is_active"=>1];
        $data[] = ["name"=>"Cereals","is_active"=>1];
        $data[] = ["name"=>"Oats","is_active"=>1];
        $data[] = ["name"=>"Porridge","is_active"=>1];
        $data[] = ["name"=>"Muesli","is_active"=>1];
        $data[] = ["name"=>"Chutney","is_active"=>1];
        $data[] = ["name"=>"Sauce","is_active"=>1];
        $data[] = ["name"=>"Dips & Dressing","is_active"=>1];
        $data[] = ["name"=>"Honey","is_active"=>1];
        $data[] = ["name"=>"Jam","is_active"=>1];
        $data[] = ["name"=>"Pickles","is_active"=>1];
        $data[] = ["name"=>"Soups","is_active"=>1];
        $data[] = ["name"=>"Seasoning","is_active"=>1];
        $data[] = ["name"=>"Salts","is_active"=>1];
        $data[] = ["name"=>"Spices","is_active"=>1];
        $data[] = ["name"=>"Spreads","is_active"=>1];
        $data[] = ["name"=>"Acetic Acid","is_active"=>1];
        $data[] = ["name"=>"Pastes & Purees","is_active"=>1];
        $data[] = ["name"=>"Butter","is_active"=>1];
        $data[] = ["name"=>"Cheese","is_active"=>1];
        $data[] = ["name"=>"Cream","is_active"=>1];
        $data[] = ["name"=>"Milk","is_active"=>1];
        $data[] = ["name"=>"Margarine","is_active"=>1];
        $data[] = ["name"=>"Yoghurt","is_active"=>1];
        $data[] = ["name"=>"Dairy","is_active"=>1];
        $data[] = ["name"=>"Desserts","is_active"=>1];
        $data[] = ["name"=>"Rice","is_active"=>1];
        $data[] = ["name"=>"Grains","is_active"=>1];
        $data[] = ["name"=>"Meat","is_active"=>1];
        $data[] = ["name"=>"Chicken","is_active"=>1];
        $data[] = ["name"=>"Egg","is_active"=>1];
        $data[] = ["name"=>"Canned Food","is_active"=>1];
        $data[] = ["name"=>"Dehydrated Food","is_active"=>1];
        $data[] = ["name"=>"Vegetables","is_active"=>1];
        $data[] = ["name"=>"Fruits","is_active"=>1];
        $data[] = ["name"=>"Salty Snacks","is_active"=>1];
        $data[] = ["name"=>"Seafood","is_active"=>1];
        $data[] = ["name"=>"Ctc","is_active"=>1];
        $data[] = ["name"=>"Orthodox","is_active"=>1];
        $data[] = ["name"=>"Tissanes","is_active"=>1];
        $data[] = ["name"=>"Pulses","is_active"=>1];
        $data[] = ["name"=>"Instant Food'","is_active"=>1];

        \DB::table("product_sub_categories")->insert($data);
    }
}
