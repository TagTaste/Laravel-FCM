<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class QuestionnaireQuestionOptions extends Model
{
    protected $table = "questionnaire_question_options";
    protected $guarded = ["id"];


    public function getOptionType()
    {
        $data = \DB::table('questionnaire_option_types')->select('slug_id')->where('id',$this->option_type_id)
        ->whereNull('deleted_at')->where('is_active',1)->first();
        return $data->slug_id;
    }

    public function updateIntensityValues(){
        $intensityValueList = json_decode($this->intensity_value);
        $intensityValue = '';
        $intensityColor = '';
        foreach($intensityValueList as $intensityObj){
            $intensityValue .= $intensityObj->title.',';
            $intensityColor .= $intensityObj->color.',';
        }
        
        if(strlen($intensityValue) > 0 && strlen($intensityColor) > 0){
            $intensityValue = substr($intensityValue, 0, strlen($intensityValue)-1);
            $intensityColor = substr($intensityColor, 0, strlen($intensityColor)-1);;
        }
        
        return ["intensity_value"=>$intensityValue, "intensity_color"=>$intensityColor];
    }

    public function getImage(){
        return json_decode($this->image)->original_photo ?? '';
    }
}


