<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class QuestionnaireIntensityListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $intensityList = [
        ['title'=>'7 Point Scale', 'sort_order'=>1,'intensity_value'=>[
            ['title'=>'Barely Detectable','color'=>'#FCEFCC','sort_order'=>1],
            ['title'=>'Weak','color'=>'#FAE7B2','sort_order'=>2],
            ['title'=>'Mild','color'=>'#F9DF99','sort_order'=>3],
            ['title'=>"Moderate",'color'=>"#F7D77F",'sort_order'=>4],
            ['title'=>'Intense','color'=>'#F4C74C','sort_order'=>5],
            ['title'=>'Very Intense','color'=>'#F2BC26','sort_order'=>6],
            ['title'=>'Extremely Intense','color'=>'#EDAE00','sort_order'=>7]
        ]]];

        foreach($intensityList as $intensity){
            $intensityObj = ['title'=>$intensity['title'],'sort_order'=>$intensity['sort_order'],'is_active'=>1, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()];
            
            $data = \DB::table('questionnaire_intensity_lists')->insert($intensityObj);
            if($data){
                $intenityObj = \DB::table('questionnaire_intensity_lists')->whereNull('deleted_at')
                ->select('id')->orderBy('id', 'desc')->first();
                $intensityValueList = $intensity['intensity_value'];
                foreach($intensityValueList as $intensity){
                    $intensityExtraElements = ['intensity_list_id'=>$intenityObj->id,'is_active'=>1, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()];
                    
                    $finalIntensityValue = array_merge($intensity, $intensityExtraElements);
    
                    \DB::table('questionnaire_intensity_values')->insert($finalIntensityValue);
                }    
            }            
        }        
    }
}
