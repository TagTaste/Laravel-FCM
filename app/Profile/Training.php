<?php

namespace App\Profile;

use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    protected $table = 'training_undertaken';
    protected $fillable = ['title','trained_from','completed_on','profile_id'];

    protected $visible = ['id','title','trained_from','completed_on','profile_id'];

    public function profile()
    {
        return $this->belongsTo('App\Profile');
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
