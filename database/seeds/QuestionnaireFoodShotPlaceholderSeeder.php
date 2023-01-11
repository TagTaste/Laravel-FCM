<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class QuestionnaireFoodShotPlaceholderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $data = [];
        $data[] = ['title'=>'Selfie With Product','image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/shelfi+with+product.png','is_active'=>1, 'sort_order'=>3, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()];

        $data[] = ['title'=>'Product Shot','image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/shelfi+with+product.png','is_active'=>1, 'sort_order'=>2, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()];
        
        $data[] = ['title'=>'Product Bill Shot','image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/shelfi+with+product.png','is_active'=>1, 'sort_order'=>1, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()];

        \DB::table('questionnaire_food_shot_placeholders')->insert($data);
    }
}
