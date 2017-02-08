<?php

namespace App;

use App\Scopes\Profile as ScopeProfile;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    use ScopeProfile {
        ScopeProfile::scopeProfile as sProfile;
    }

    protected $fillable = ['name','description','profile_id'];

    protected $visible = ['id','name','description','profile_id','photos'];

    public static function boot()
    {
        parent::boot();

        static::deleting(function($album){
            $album->photos()->delete();

            $album->ideabooks()->detach();
            return;
        });
        
    }
    
    public function photos()
    {
        return $this->hasMany('App\Photo');
    }

    public function ideabooks()
    {
        return $this->belongsToMany('\App\Album','ideabook_albums','album_id','ideabook_id');
    }

    public function profile()
    {
        return $this->belongsToMany('App\Profile','profile_albums','album_id','profile_id');
    }

    public function company()
    {
        return $this->belongsToMany('App\Profile','company_albums','album_id','company_id');
    }
}
