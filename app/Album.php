<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Album extends Model
{
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
        return $this->belongsTo('App\Profile');
    }
}
