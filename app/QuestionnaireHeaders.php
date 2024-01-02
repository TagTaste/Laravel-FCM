<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuestionnaireHeaders extends Model
{
    protected $table = "questionnaire_headers";
    protected $guarded = ["id"];


    public function getHeaderSelectionType(){
        $data = \DB::table('questionnaire_header_types')->where('id',$this->header_type_id)
        ->whereNull('deleted_at')->first();
        return $data->header_selection_type;
    }    

}
