<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SurveysLike extends Model
{
    protected $table = 'surveys_likes';

    protected $fillable = ['survey_id','profile_id'];

    public function surveys()
    {
        return $this->belongsToMany('App\Surveys','surveys_id');
    }
}
