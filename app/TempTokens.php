<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TempTokens extends Model
{
    protected $table = "temp_tokens";
    protected $guarded = ["id"];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email','questionnaire_share_id', 'profile_id', 'source', 'token', 'created_at', 'updated_at','deleted_at','expired_at'
    ];

}
