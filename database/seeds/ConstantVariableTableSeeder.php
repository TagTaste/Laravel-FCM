<?php

use Illuminate\Database\Seeder;

class ConstantVariableTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];
        $data[] = ['model_name'=>'profile', 'model_id'=>1009, 'ui_type'=>1, 'data_json'=>'{"title": "Chef of the Week", "description": "Chef Gunjan Goela is a celebrity chef, who is very popular for her shows nationally and internationally. With over 20 years of experience, Chef Gunjan has had the privilege to cook for our PM Mr. Narendra Modi. She has catered to some of the most important Indian industrialists like Ambanis, Goenkas, Modis, and Birlas. Gunjan also had a chance to do a popular TV series on Indian food with internationally famous Chef Gary Rhodes. She also manages a successful catering business and takes great pride in the rich culinary heritage of India."}', 'is_active'=>1];


        $data[] = ['model_name'=>'profile', 'model_id'=>111, 'ui_type'=>2, 'data_json'=>'{"title": "Recommendations", "subtitle": "Based on your background & interests"}', 'is_active'=>1];

        $data[] = ['model_name'=>'profile', 'model_id'=>"804,70,5555,27,685,626,2376,71,530,1315,48,961,383,1195,354,358,123,238,4338,787" , 'ui_type'=>3, 'data_json'=>'{"title": "Active & Influential"}', 'is_active'=>1];

        $data[] = ['model_name'=>'company', 'model_id'=>111, 'ui_type'=>4, 'data_json'=>'{
	"title": "Company in Focus",
	"subtitle": null,
	"description": "Maecenas faucibus mollis interdum. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.Maecenas sed diam eget risus varius blandit sit amet non magna.Maecenas sed diam eget risus varius."
}', 'is_active'=>0];

        $data[] = ['model_name'=>'company', 'model_id'=>111 , 'ui_type'=>5, 'data_json'=>'{"title": "Companies to follow"}', 'is_active'=>1];

        $data[] = ['model_name'=>'company', 'model_id'=>111 , 'ui_type'=>6, 'data_json'=>'{"title": "Companies to follow"}', 'is_active'=>0];

        $data[] = ['model_name'=>'collaborate', 'model_id'=>111 , 'ui_type'=>7, 'data_json'=>'{"title": "Collaborations", "subtitle": "Interesting opportunities for you"}', 'is_active'=>1];

        $data[] = ['model_name'=>'collaborate-private', 'model_id'=>111 , 'ui_type'=>8, 'data_json'=>'{"title": "Collaborations", "subtitle": "Product Review"}', 'is_active'=>0];

        $data[] = ['model_name'=>'product', 'model_id'=>111 , 'ui_type'=>9, 'data_json'=>'{"title": "Featured Products", "subtitle": "Products in focus this week"}', 'is_active'=>0];

        $data[] = ['model_name'=>'product', 'model_id'=>111 , 'ui_type'=>10, 'data_json'=>'{"title": "Based on your Interest", "subtitle": "DARK CHOCOLATE, WINE AND 2 OTHERS"}', 'is_active'=>0];

        $data[] = ['model_name'=>'product', 'model_id'=>111 , 'ui_type'=>11, 'data_json'=>'{"title": "Newly Added Products", "subtitle": "BE THE FIRST ONE TO REVIEW"}', 'is_active'=>0];

        $data[] = ['model_name'=>'category', 'model_id'=>111 , 'ui_type'=>12, 'data_json'=>'{"title": "CATEGORY OF THE WEEK", "subtitle": "Category of the Week ", "image_meta": {"meta": {"width": 300, "height": 280, "tiny_photo": "https://s3.ap-south-1.amazonaws.com/static4.tagtaste.com/dashboard/images/tiny/l3xdc77zd4cm92rhh1p7m.jpg"}, "original_photo": "https://s3.ap-south-1.amazonaws.com/static4.tagtaste.com/dashboard/images/l3xdc77zd4cm92rhh1p7m.jpg"}, "description": "Category of the week"}', 'is_active'=>0];

        $data[] = ['model_name'=>'category', 'model_id'=>111 , 'ui_type'=>13, 'data_json'=>'{"image": null, "title": "Categories", "description": "LENSES FOR THE F&B INDUSTRY"}', 'is_active'=>0];

        $data[] = ['model_name'=>'category', 'model_id'=>111 , 'ui_type'=>14, 'data_json'=>'{"image": null, "title": "Featured Category", "description": "Products in focus this week"}', 'is_active'=>0];

        $data[] = ['model_name'=>'specialization', 'model_id'=>111 , 'ui_type'=>15, 'data_json'=>'{"title": "Explore by Specialization"}', 'is_active'=>1];

        $data[] = ['model_name'=>'facebook', 'model_id'=>111 , 'ui_type'=>16, 'data_json'=>'{"title": "See your facebook friend"}', 'is_active'=>0];

        \DB::table('constant_variable_model')->insert($data);
    }
}
