<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\QuestionnaireQuestionHelper;

class QuestionnaireQuestions extends Model
{
    protected $table = "questionnaire_questions";
    protected $guarded = ["id"];


    public function getOptions(){
        if($this->is_nested_option){
            $nestedOptions = \DB::table('global_nested_option')->select("*", "s_no as sequence_id")
            ->where('type',$this->nested_option_list)
            ->Where('parent_id',0)
            ->whereNull('deleted_at')->where('is_active',1)
            ->orderBy('pos','asc')->get();
        
            return $nestedOptions;
        }else{
            return [];
        }
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

    public function getHelper(){
        $questionHelper = QuestionnaireQuestionHelper::select('assets_order','images','title as text','video_link','videos_meta')
        ->where('question_id', $this->id)
        ->where('is_active', 1)
        ->whereNull('deleted_at')->first();

        if(!is_null($questionHelper)){
            $questionHelper['assets_order'] = json_decode($questionHelper->assets_order);
            $questionHelper['images'] = json_decode($questionHelper->images);
            $questionHelper['videos_meta'] = json_decode($questionHelper->videos_meta);
        }
        return $questionHelper;
    }

    public function getSelectType(){
        $seletType = \DB::table('questionnaire_question_types')
        ->where("id", $this->select_type)->get()->pluck("select_type");
        return $seletType[0] ?? null;
    }
}
