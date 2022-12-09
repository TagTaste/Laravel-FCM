<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SurveyAttemptMapping extends Model
{
    protected $table = "surveys_attempt_mapping";
    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    protected $guarded = ["id"];
    protected $fillable = ["profile_id","survey_id","completion_date","created_at","attempt"];


    public function profile()
    {
        return $this->belongsTo(\App\Recipe\Profile::class, "profile_id");
    }

    public function survey()
    {
        return $this->belongsToMany(\App\Surveys::class, 'survey_id');
    }
}
