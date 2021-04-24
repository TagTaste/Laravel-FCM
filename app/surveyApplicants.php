<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class surveyApplicants extends Model
{
    protected $table = "survey_applicants";

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';



    public function profile()
    {
        return $this->belongsTo(\App\Recipe\Profile::class);
    }
    

}
