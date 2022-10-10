<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class QuestionnaireOptionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $data[] = ['title'=>'Default','slug_id'=>0,'is_active'=>1, 'sort_order'=>1, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()];

        $data[] = ['title'=>'Absent','slug_id'=>1,'is_active'=>1, 'sort_order'=>2, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()];

        $data[] = ['title'=>'Any other','slug_id'=>2,'is_active'=>1, 'sort_order'=>3, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()];
        
        \DB::table('questionnaire_option_types')->insert($data);
    }
}
