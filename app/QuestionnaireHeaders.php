<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuestionnaireHeaders extends Model
{
    protected $table = "questionnaire_headers";
    protected $guarded = ["id"];
}
