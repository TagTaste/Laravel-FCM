<?php

namespace App;

use App\Notifications\NewAlbum;
use App\Scopes\Profile as ScopeProfile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Album extends Model
{
    use ScopeProfile, Notifiable;

    protected $fillable = ['name','description','profile_id'];

    protected $visible = ['id','name','description','photos'];

    public static function boot()
    {
        parent::boot();

        static::deleting(function($album){
            $album->photos()->delete();

            $album->ideabooks()->detach();
            return;
        });
        
        
        static::created(function($album){
            $user = User::find(1);
            $user->notify(new NewAlbum($album));
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
        return $this->belongsToMany('App\Company','company_albums','album_id','company_id');
    }

    public static function createDefault()
    {
        return static::create(['name'=>"Default Album", 'description'=>"Default Album"]);
    }

    public function scopeForCompany($query, $companyId)
    {
        return $query->whereHas('company',function($q) use ($companyId){
            $q->where('company_id',$companyId);
        });
    }
}
