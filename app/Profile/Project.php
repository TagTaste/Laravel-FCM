<?php

namespace App\Profile;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['title','description','completed_on','url','profile_id'];

    protected $visible = ['id','title','description','completed_on','url','profile_id'];

    public function profile()
    {
        return $this->belongsTo('App\Profile');
    }

    public function members()
    {
        return $this->hasMany('App\ProjectMember');
    }

    public function setCompletedOnAttribute($value)
    {
        if(!empty($value)){
            $value = "01-".$value;
            $this->attributes['completed_on'] = date('Y-m-d',strtotime($value));
        }
    }

    public function getCompletedOnAttribute($value)
    {
        if(!empty($value)){
            return date("m-Y",strtotime($value));
        }
    }
}
