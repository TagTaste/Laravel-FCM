<?php

namespace App\Collaborate;

use Illuminate\Database\Eloquent\Model;


class Review extends Model {

    protected $table = 'collaborate_tasting_user_review';

    protected $fillable = ['key','value','leaf_id','question_id','tasting_header_id','collaborate_id','profile_id','batch_id','intensity','current_status','created_at','updated_at'];

    protected $visible = ['id','key','value','leaf_id','question_id','tasting_header_id','collaborate_id','profile_id','batch_id',
        'intensity','current_status','created_at','updated_at','profile'];
    
    protected $with = ['profile','company'];

    //current_status 0 - to be notified, 1 - notified, 2 - in progress, 3 - completed

    public function profile()
    {
        return $this->belongsTo(\App\Recipe\Profile::class);
    }
    public function company()
    {
        return $this->belongsTo(\App\Recipe\Company::class);
    }
}
