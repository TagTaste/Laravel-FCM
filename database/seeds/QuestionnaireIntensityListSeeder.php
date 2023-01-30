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
        ['title'=>"Preference", 'sort_order'=>2,'intensity_value'=>[
            ['title'=>"Dislike Extremely",'color'=>'#FCEFCC','sort_order'=>1],
            ['title'=>"Dislike Moderately",'color'=>'#FAE7B2','sort_order'=>2],
            ['title'=>"Dislike Slightly",'color'=>'#F9DF99','sort_order'=>3],
            ['title'=>"Can't Say",'color'=>"#F7D77F",'sort_order'=>4],
            ['title'=>"Like Slightly",'color'=>'#F4C74C','sort_order'=>5],
            ['title'=>"Like Moderately",'color'=>'#F2BC26','sort_order'=>6],
            ['title'=>"Like Extremely",'color'=>'#EDAE00','sort_order'=>7]
        ]],['title'=>"Intensity Scale", 'sort_order'=>3,'intensity_value'=>[
            ['title'=>"Barely Detectable",'color'=>'#FCEFCC','sort_order'=>1],
            ['title'=>"Weak",'color'=>'#FAE7B2','sort_order'=>2],
            ['title'=>"Mild",'color'=>'#F9DF99','sort_order'=>3],
            ['title'=>"Moderate",'color'=>"#F7D77F",'sort_order'=>4],
            ['title'=>"Intense",'color'=>'#F4C74C','sort_order'=>5],
            ['title'=>"Very Intense",'color'=>'#F2BC26','sort_order'=>6],
            ['title'=>"Extremely Intense",'color'=>'#EDAE00','sort_order'=>7]
        ]],['title'=>"Intensity (Taste - Sour)", 'sort_order'=>4,'intensity_value'=>[
            ['title'=>"Barely Acidic",'color'=>'#FCEFCC','sort_order'=>1],
            ['title'=>"Weakly Acidic",'color'=>'#FAE7B2','sort_order'=>2],
            ['title'=>"Mildly Acidic",'color'=>'#F9DF99','sort_order'=>3],
            ['title'=>"Moderately Acidic",'color'=>"#F7D77F",'sort_order'=>4],
            ['title'=>"Intensely Acidic",'color'=>'#F4C74C','sort_order'=>5],
            ['title'=>"Very Intensely Acidic",'color'=>'#F2BC26','sort_order'=>6],
            ['title'=>"Extremely Acidic",'color'=>'#EDAE00','sort_order'=>7]
        ]],['title'=>"Quantity", 'sort_order'=>5,'intensity_value'=>[
            ['title'=>"Barely Any",'color'=>'#FCEFCC','sort_order'=>1],
            ['title'=>"Very Less",'color'=>'#FAE7B2','sort_order'=>2],
            ['title'=>"Less",'color'=>'#F9DF99','sort_order'=>3],
            ['title'=>"Moderate",'color'=>"#F7D77F",'sort_order'=>4],
            ['title'=>"Little Extra",'color'=>'#F4C74C','sort_order'=>5],
            ['title'=>"Extra",'color'=>'#F2BC26','sort_order'=>6],
            ['title'=>"Excess",'color'=>'#EDAE00','sort_order'=>7]
        ]],['title'=>"Slipperiness", 'sort_order'=>6,'intensity_value'=>[
            ['title'=>"Barely",'color'=>'#FCEFCC','sort_order'=>1],
            ['title'=>"Drags",'color'=>'#FAE7B2','sort_order'=>2],
            ['title'=>"Slightly Slips",'color'=>'#F9DF99','sort_order'=>3],
            ['title'=>"Moderately Slips",'color'=>"#F7D77F",'sort_order'=>4],
            ['title'=>"Slips",'color'=>'#F4C74C','sort_order'=>5],
            ['title'=>"Very Slippery",'color'=>'#F2BC26','sort_order'=>6],
            ['title'=>"Extremely Slippery",'color'=>'#EDAE00','sort_order'=>7]
        ]],['title'=>"Firmness", 'sort_order'=>7,'intensity_value'=>[
            ['title'=>"Extremely Soft",'color'=>'#FCEFCC','sort_order'=>1],
            ['title'=>"Very Soft",'color'=>'#FAE7B2','sort_order'=>2],
            ['title'=>"Slightly Soft like Jelly",'color'=>'#F9DF99','sort_order'=>3],
            ['title'=>"Firm like cake",'color'=>"#F7D77F",'sort_order'=>4],
            ['title'=>"Moderately Firm",'color'=>'#F4C74C','sort_order'=>5],
            ['title'=>"Very Firm",'color'=>'#F2BC26','sort_order'=>6],
            ['title'=>"Extremely  Firm",'color'=>'#EDAE00','sort_order'=>7]
        ]],['title'=>"Fracturability", 'sort_order'=>8,'intensity_value'=>[
            ['title'=>"Extremely Shears",'color'=>'#FCEFCC','sort_order'=>1],
            ['title'=>"Moderately Shears",'color'=>'#FAE7B2','sort_order'=>2],
            ['title'=>"Barely Shears",'color'=>'#F9DF99','sort_order'=>3],
            ['title'=>"Barely Deforms",'color'=>"#F7D77F",'sort_order'=>4],
            ['title'=>"Moderately Deforms",'color'=>'#F4C74C','sort_order'=>5],
            ['title'=>"Clearly Deforms",'color'=>'#F2BC26','sort_order'=>6],
            ['title'=>"Extremely Deforms",'color'=>'#EDAE00','sort_order'=>7]
        ]],['title'=>"Force", 'sort_order'=>9,'intensity_value'=>[
            ['title'=>"Barely Any Force",'color'=>'#FCEFCC','sort_order'=>1],
            ['title'=>"Very Slight Force",'color'=>'#FAE7B2','sort_order'=>2],
            ['title'=>"Slight Force",'color'=>'#F9DF99','sort_order'=>3],
            ['title'=>"Moderate Force",'color'=>"#F7D77F",'sort_order'=>4],
            ['title'=>"Strong Force",'color'=>'#F4C74C','sort_order'=>5],
            ['title'=>"Very Strong Force",'color'=>'#F2BC26','sort_order'=>6],
            ['title'=>"Extremely Strong Force",'color'=>'#EDAE00','sort_order'=>7]
        ]],['title'=>"Stickiness", 'sort_order'=>10,'intensity_value'=>[
            ['title'=>"Barely Sticky",'color'=>'#FCEFCC','sort_order'=>1],
            ['title'=>"Weakly Sticky",'color'=>'#FAE7B2','sort_order'=>2],
            ['title'=>"Sighty Sticky",'color'=>'#F9DF99','sort_order'=>3],
            ['title'=>"Moderately Sticky",'color'=>"#F7D77F",'sort_order'=>4],
            ['title'=>"Strongly Sticky",'color'=>'#F4C74C','sort_order'=>5],
            ['title'=>"Very Strongly",'color'=>'#F2BC26','sort_order'=>6],
            ['title'=>"Extremely Sticky",'color'=>'#EDAE00','sort_order'=>7]
        ]],['title'=>"Color", 'sort_order'=>11,'intensity_value'=>[
            ['title'=>"Very light",'color'=>'#FCEFCC','sort_order'=>1],
            ['title'=>"Light",'color'=>'#FAE7B2','sort_order'=>2],
            ['title'=>"Mild",'color'=>'#F9DF99','sort_order'=>3],
            ['title'=>"Moderate",'color'=>"#F7D77F",'sort_order'=>4],
            ['title'=>"Intense",'color'=>'#F4C74C','sort_order'=>5],
            ['title'=>"Very intense",'color'=>'#F2BC26','sort_order'=>6],
            ['title'=>"Extremely Intense",'color'=>'#EDAE00','sort_order'=>7]
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
