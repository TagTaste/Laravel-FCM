<?php

namespace App\Company;

use Illuminate\Database\Eloquent\Model;

class Award extends Model
{
    protected $fillable = ['name','description','date'];

    protected $visible = ['id','name','description','date'];

    public function company()
    {
        return $this->belongsToMany('App\Company','company_awards','award_id','company_id');
    }

    public function setDateAttribute($value)
    {
        if(!empty($value)) {
            $value = "01-". $value ;
            $this->attributes['date'] = date('m-Y', strtotime($value));
        }
    }

    public function getDateAttribute($value)
    {
        return date("m-Y",strtotime($value));
    }

    public function scopeForCompany($query,$profileId)
    {
        return $query->whereHas('company',function($query) use ($profileId){
            $query->where('id',$profileId);
        });
    }
}
