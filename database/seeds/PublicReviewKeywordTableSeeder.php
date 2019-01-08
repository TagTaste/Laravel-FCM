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
        $data[] = ['key'=>'keywords','value'=>'Product'];
        $data[] = ['key'=>'keywords','value'=>'Category'];
        $data[] = ['key'=>'keywords','value'=>'Sub-Category'];
        \DB::table('public_review_keywords')->insert($data);
    }
}
