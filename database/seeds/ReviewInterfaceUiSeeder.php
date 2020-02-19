<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ReviewInterfaceUiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];

        $data[] = [
            'name'=>'Newly Launched Product',
            'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/review-page-layout-images/collection_bg.png',
            'created_at'=> Carbon::now(),
            'updated_at'=> Carbon::now(),
        ];
        $data[] = [
            'name'=>'Collaboration',
            'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/review-page-layout-images/collaboration.png',
            'created_at'=> Carbon::now(),
            'updated_at'=> Carbon::now(),
        ];
        $data[] = [
            'name'=>'Product Collection',
            'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/review-page-layout-images/collection_nobg.png',
            'created_at'=> Carbon::now(),
            'updated_at'=> Carbon::now(),
        ];
        $data[] = [
            'name'=>'Top Tasters',
            'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/review-page-layout-images/section_tasters.png',
            'created_at'=> Carbon::now(),
            'updated_at'=> Carbon::now(),
        ];
        $data[] = [
            'name'=>'Campus Connect',
            'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/review-page-layout-images/campus_connect.png',
            'created_at'=> Carbon::now(),
            'updated_at'=> Carbon::now(),
        ];
        $data[] = [
            'name'=>'Handpicked Collections',
            'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/review-page-layout-images/handpicked_collection.png',
            'created_at'=> Carbon::now(),
            'updated_at'=> Carbon::now(),
        ];
        $data[] = [
            'name'=>'Brand Filters',
            'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/review-page-layout-images/section_brands.png',
            'created_at'=> Carbon::now(),
            'updated_at'=> Carbon::now(),
        ];
        $data[] = [
            'name'=>'Categories Filters',
            'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/review-page-layout-images/section_categories.png',
            'created_at'=> Carbon::now(),
            'updated_at'=> Carbon::now(),
        ];
        $data[] = [
            'name'=>'Blog',
            'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/review-page-layout-images/section_blog.png',
            'created_at'=> Carbon::now(),
            'updated_at'=> Carbon::now(),
        ];

        foreach ($data as $key => $value) {
            $found = \DB::table('review_interface_ui')->where('name',$value['name'])->count();
            if ($found) {
                $name = $value['name'];
                unset($value['name']);
                \DB::table('review_interface_ui')->where('name', $name)->update($value);
            } else {
                \DB::table('review_interface_ui')->insert($value);
            }
        }
    }
}
