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
        $intensityList = [['title'=>'5 Point Scale', 'sort_order'=>1,'intensity_value'=>[
            ['title'=>'Dislike Moderately','color'=>'#C92E41','sort_order'=>1],
            ['title'=>'Dislike Slightly','color'=>'#C92E41','sort_order'=>2],
            ['title'=>"Can't Say",'color'=>"#E27616",'sort_order'=>3],
            ['title'=>'Like Slightly','color'=>'#C92E41','sort_order'=>4],
            ['title'=>'Like Moderately','color'=>'#7E9B42','sort_order'=>5],
        ]],
        ['title'=>'7 Point Scale', 'sort_order'=>1,'intensity_value'=>[
            ['title'=>'Dislike Extremely','color'=>'#8C0008','sort_order'=>1],
            ['title'=>'Dislike Moderately','color'=>'#C92E41','sort_order'=>2],
            ['title'=>'Dislike Slightly','color'=>'#C92E41','sort_order'=>3],
            ['title'=>"Can't Say",'color'=>"#E27616",'sort_order'=>4],
            ['title'=>'Like Slightly','color'=>'#C92E41','sort_order'=>5],
            ['title'=>'Like Moderately','color'=>'#7E9B42','sort_order'=>6],
            ['title'=>'Like Extremely','color'=>'#305D03','sort_order'=>7]
        ]],
        ['title'=>'9 Point Scale', 'sort_order'=>1,'intensity_value'=>[
            ['title'=>'Best','color'=>'#305F04','sort_order'=>1],
            ['title'=>'Dislike Extremely','color'=>'#8C0008','sort_order'=>2],
            ['title'=>'Dislike Moderately','color'=>'#C92E41','sort_order'=>3],
            ['title'=>'Dislike Slightly','color'=>'#C92E41','sort_order'=>4],
            ['title'=>"Can't Say",'color'=>"#E27616",'sort_order'=>5],
            ['title'=>'Like Slightly','color'=>'#C92E41','sort_order'=>6],
            ['title'=>'Like Moderately','color'=>'#7E9B42','sort_order'=>7],
            ['title'=>'Like Extremely','color'=>'#305D03','sort_order'=>8],
            ['title'=>'Worst','color'=>'#305E06','sort_order'=>9]
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
