<?php

namespace App\Profile;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    protected $fillable = ['company','designation','description','location',
    'start_date','end_date','current_company','profile_id'];

    protected $visible = ['company','designation','description','location',
        'start_date','end_date','current_company'];

    public function profile()
    {
        return $this->belongsTo('App\Profile');
    }

    public function setStartDateAttribute($value)
{
    $this->attributes['start_date'] = date('Y-m-d',strtotime($value));
}

    public function getStartDateAttribute($value)
    {
        if(!$value){
            return;
        }
        return date("d-m-Y",strtotime($value));
    }

    public function setEndDateAttribute($value)
    {
        $this->attributes['end_date'] = date('Y-m-d',strtotime($value));
    }

    public function getEndDateAttribute($value)
    {
        if(!$value){
            return;
        }
        return date("d-m-Y",strtotime($value));
    }
}
