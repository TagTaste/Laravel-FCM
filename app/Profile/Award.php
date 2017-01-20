<?php

namespace App\Profile;

use Illuminate\Database\Eloquent\Model;

class Award extends Model
{
    protected $fillable = ['name','description','date','profile_id'];

    public function setDateAttribute($value)
    {
        $this->attributes['date'] = date('Y-m-d',strtotime($value));
    }

    public function getDateAttribute($value)
    {
        if(!$value){
            return;
        }
        return date("d-m-Y",strtotime($value));
    }


}
