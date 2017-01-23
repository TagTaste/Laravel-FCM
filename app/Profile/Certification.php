<?php

namespace App\Profile;

use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    protected $fillable = ['name','description','date','profile_id'];

    protected $visible = ['name','description','date'];

    public function setDateAttribute($value)
    {
        $this->attributes['date'] = date('Y-m-d',strtotime($value));
    }

    public function getDateAttribute($value)
    {
        if(!$value){
            return;
        }
        return date("m-Y",strtotime($value));
    }
}
