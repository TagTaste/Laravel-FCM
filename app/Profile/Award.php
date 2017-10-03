<?php

namespace App\Profile;

use App\Scopes\Profile;
use App\Traits\PositionInCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Award extends Model
{
    use Profile, PositionInCollection;

    protected $fillable = ['name','description','date','profile_id'];

    protected $visible = ['id','name','description','date','total'];
    
    protected $appends = ['total'];

    protected static function boot()
    {
        parent::boot();
        // Order by name ASC
        static::addGlobalScope('awards', function (Builder $builder) {
            $builder->orderBy('date', 'desc');
        });
    }
    
    public function profile()
    {
        return $this->belongsToMany('App\Profile','profile_awards','award_id','profile_id');
    }
    
    public function getTotalAttribute()
    {
        $profileId = $this->profile->first()->id;
        $collection = $this->select('id')->whereHas('profile',function($query) use ($profileId){
            $query->where('profile_id',$profileId);
        })->orderBy('created_at','asc')->get();
        return $this->getCount($collection);
        
    }

}
