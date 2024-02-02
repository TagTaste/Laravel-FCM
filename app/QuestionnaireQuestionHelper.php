<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuestionnaireQuestionHelper extends Model
{
    protected $table = "questionnaire_question_helpers";
    protected $guarded = ["id"];
}
