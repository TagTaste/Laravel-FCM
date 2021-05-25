<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Profile;

class SurveyAnswers extends Model
{
    protected $table = "survey_answers";
    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    protected $guarded = ["id"];


    public function profile()
    {
        return $this->belongsTo(\App\Recipe\Profile::class,"profile_id");
    }

    public function company()
    {
        return $this->belongsTo(\App\Recipe\Company::class,'company_id');
    }
}
