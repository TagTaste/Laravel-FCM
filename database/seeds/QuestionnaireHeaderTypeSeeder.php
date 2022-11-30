<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class QuestionnaireHeaderTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $data[] = ['title'=>'Instruction Header','header_selection_type'=>0,'header_slug'=>'instruction','is_active'=>1, 'sort_order'=>1, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()];
        
        $data[] = ['title'=>'Normal Header','header_selection_type'=>1,'header_slug'=>'normal_header','is_active'=>1, 'sort_order'=>2, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()];
        
        \DB::table('questionnaire_header_types')->insert($data);
    }
}
