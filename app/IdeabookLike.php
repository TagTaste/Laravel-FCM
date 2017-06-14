<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IdeabookLike extends Model
{
    protected $fillable = ['profile_id', 'ideabook_id'];

    public function tagboard()
    {
        return $this->belongsToMany('App\Ideabook','ideabook_id');
    }
}
