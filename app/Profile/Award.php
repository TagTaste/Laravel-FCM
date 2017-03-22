<?php

namespace App\Profile;

use App\Scopes\Profile;
use Illuminate\Database\Eloquent\Model;

class Award extends Model
{
    use Profile;

    protected $fillable = ['name','description','date','profile_id'];

    protected $visible = ['id','name','description','date','total'];
    
    protected $appends = ['total'];

    public function setDateAttribute($value)
    {
        if(!empty($value)){
            $this->attributes['date'] = date('Y-m-d',strtotime($value));
        }
    }

    public function getDateAttribute($value)
    {
        if(!$value){
            return date("d-m-Y",strtotime($value));
        }
    }
    
    public function profile()
    {
        return $this->belongsToMany('App\Profile','profile_awards','award_id','profile_id');
    }
    
    public function getTotalAttribute()
    {
        $profileId = $this->profile->first()->id;
        return $this->ForProfile($profileId)->count();
    }

}
