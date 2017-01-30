<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    protected $fillable = ['name','description','profile_id'];
    
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
