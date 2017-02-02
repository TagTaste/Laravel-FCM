<?php

namespace App\Profile;

use Illuminate\Database\Eloquent\Model;

class ProjectMember extends Model
{
    protected $fillable = ['name','designation','description','project_id','profile_id'];

    public function projects()
    {
        return $this->belongsToMany('App\Project');
    }

    public function profile()
    {
        return $this->belongsTo('App\Profile');
    }
}
