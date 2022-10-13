<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuizLike extends Model
{
    protected $table = 'quiz_likes';

    protected $fillable = ['quiz_id','profile_id'];

    public function quiz()
    {
        return $this->belongsToMany('App\Quiz','quiz_id');
    }
}
