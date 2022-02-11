<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class surveyApplicants extends Model
{
    protected $table = "survey_applicants";

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    protected $guarded = ["id"];

    
    // protected $visible = ['id','profile_id','survey_id','is_invited','profile','applier_address',
    // 'message','hut','created_at','updated_at','city','age_group','gender','company','company_id','document_meta','terms_verified', 'documents_verified','phone','submission_count','hometown','current_city'];

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
}
