<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuestionnairePreviewShareUsers extends Model
{
    protected $table = "questionnaire_preview_share_users";
    protected $guarded = ["id"];

    protected $fillable = [
        'email','questionnaire_id', 'otp', 'created_at', 'updated_at','deleted_at','expired_at','attempts'
    ];
}
