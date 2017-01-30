<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $fillable = ['caption','file','album_id'];
    public function albums()
    {
        return $this->belongsTo('App\Album');
    }
}
