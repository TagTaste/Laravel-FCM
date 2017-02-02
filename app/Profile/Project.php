<?php

namespace App\Profile;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['title','description','ongoing','start_date','end_date','url','profile_id'];

    public function profile()
    {
        return $this->belongsTo('App\Profile');
    }

    public function members()
    {
        return $this->hasMany('App\ProjectMember');
    }
}
