<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SurveyQuestionsType extends Model
{
    
    protected $table = "survey_question_type";
    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    protected $fillable = ["id","title","description","media","is_active","deleted_at","question_type_id","sort_id","element_type","new_tag_expired_at"];
    
}
