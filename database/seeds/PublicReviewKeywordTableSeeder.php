<?php

use Illuminate\Database\Seeder;

class PublicReviewKeywordTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];
        $data[] = ['key'=>'keywords','value'=>'Crakers'];
        $data[] = ['key'=>'keywords','value'=>'Chips'];
        $data[] = ['key'=>'keywords','value'=>'Dried Fruits'];
        $data[] = ['key'=>'keywords','value'=>'Finger Food Snacks'];
        $data[] = ['key'=>'keywords','value'=>'Snacks (Namkeen)'];
        $data[] = ['key'=>'keywords','value'=>'Nuts (Salted)'];
        $data[] = ['key'=>'keywords','value'=>'Pop Corns'];
        $data[] = ['key'=>'keywords','value'=>'Salty Biscuits'];
        $data[] = ['key'=>'keywords','value'=>'Butter'];
        $data[] = ['key'=>'keywords','value'=>'Peanut Butter'];
        $data[] = ['key'=>'keywords','value'=>'Jellies'];
        $data[] = ['key'=>'keywords','value'=>'Ice Cream'];
        $data[] = ['key'=>'keywords','value'=>'Chocolate'];
        $data[] = ['key'=>'keywords','value'=>'Baked Biscuits'];
        $data[] = ['key'=>'keywords','value'=>'Cakes'];
        $data[] = ['key'=>'keywords','value'=>'Granola Bar'];
        $data[] = ['key'=>'keywords','value'=>'Oatmeal'];
        $data[] = ['key'=>'keywords','value'=>'Coffee'];
        $data[] = ['key'=>'keywords','value'=>'Mushrooms'];
        $data[] = ['key'=>'keywords','value'=>'Butter Popcorn'];
        $data[] = ['key'=>'keywords','value'=>'Potato Chips'];
        $data[] = ['key'=>'keywords','value'=>'Artisian Breads'];
        $data[] = ['key'=>'keywords','value'=>'Dates'];
        $data[] = ['key'=>'keywords','value'=>'Candies'];
        $data[] = ['key'=>'keywords','value'=>'Sweet Sauces'];
        $data[] = ['key'=>'keywords','value'=>'Toffee'];
        $data[] = ['key'=>'keywords','value'=>'Bagels'];
        $data[] = ['key'=>'keywords','value'=>'Doughnuts'];
        $data[] = ['key'=>'keywords','value'=>'Pies'];
        $data[] = ['key'=>'keywords','value'=>'Waffles'];
        $data[] = ['key'=>'keywords','value'=>'Pancakes'];
        $data[] = ['key'=>'keywords','value'=>'Pastries'];
        $data[] = ['key'=>'keywords','value'=>'Aerated Drink'];
        $data[] = ['key'=>'keywords','value'=>'Carbonated Drink'];
        $data[] = ['key'=>'keywords','value'=>'Noodles'];

        $data[] = ['key'=>'keywords','value'=>'Category'];
        $data[] = ['key'=>'keywords','value'=>'Sub-Category'];
        \DB::table('public_review_keywords')->insert($data);
    }
}
