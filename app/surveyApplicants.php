<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class surveyApplicants extends Model
{
    protected $table = "survey_applicants";

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    protected $guarded = ["id"];

    // protected $visible = ['id','profile_id','survey_id','is_invited','profile,
    // 'message','hut','created_at','updated_at','city','age_group','gender','company','company_id','document_meta','terms_verified', 'documents_verified','phone','submission_count','hometown','current_city'];

    protected $appends = ['phone','inprogress_count','last_submission','submission_count'];

    protected $with = ['profile'];

    public function profile()
    {
        return $this->belongsTo(\App\Recipe\Profile::class);
    }

    public function getNotificationContent()
    {
        return [
            'name' => strtolower(class_basename(self::class)),
            'id' => $this->id,
            'content' => $this->title,
            'image' => $this->image_meta,
        ];
    }

    public function getPhoneAttribute()
    {

        $profile =  \DB::table('profiles')->where('id', $this->profile_id)->first();
        if (!is_null($profile)) {
            return $profile->phone;
        }
        return null;
    }

    public function getInProgressCountAttribute()
    {

        if ($this->application_status == 3) {
            return 1;
        } else {
            return 0;
        }
    }

    public function getSubmissionCountAttribute()
    {
       $count = SurveyAttemptMapping::where("survey_id",$this->survey_id)->where("profile_id",$this->profile_id)->whereNotNull("completion_date")->orderBy("completion_date","desc")->first();

       if(!empty($count)){
        return $count->attempt;
       }

       return 0;
       
    }

    public function getLastSubmissionAttribute()
    {
        $attempt = SurveyAttemptMapping::where("survey_id",$this->survey_id)->where("profile_id",$this->profile_id)->whereNotNull("completion_date")->orderBy("completion_date","desc")->first();
        if(!empty($attempt)){
            return $attempt->completion_date;
           }
    
           return NULL;
    }
}
