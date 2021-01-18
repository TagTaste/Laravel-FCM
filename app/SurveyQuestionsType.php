<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SurveyQuestionsType extends Model
{
    
    protected $table = "survey_question_type";
    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    protected $fillable = ["id","title","description","media","is_active","deleted_at"];
    
}
