<?php

namespace App\Collaborate;

use Illuminate\Database\Eloquent\Model;


class Review extends Model {

    protected $table = 'collaborate_tasting_user_review';

    protected $fillable = ['key','value','leaf_id','question_id','tasting_header_id','collaborate_id','profile_id','batch_id','intensity','current_status'];

    protected $visible = ['id','key','value','leaf_id','question_id','tasting_header_id','collaborate_id','profile_id','batch_id','intensity','current_status'];

}
