<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Photo extends Model
{
    protected $fillable = ['caption','file','album_id'];

    protected $visible = ['id','caption','file','created_at','album'];

    public static function boot()
    {
        parent::boot();

        static::deleting(function($photo){
//            \DB::transaction(function() use ($photo){
                $photo->ideabooks()->detach();
//            });
        });
    }

    public function album()
    {
        return $this->belongsTo('App\Album');
    }

    public function ideabooks()
    {
        return $this->belongsToMany('\App\Ideabook','ideabook_photos','photo_id','ideabook_id');
    }

    public function getCreatedAtAttribute($value)
    {
        return date("d-m-Y",strtotime($value));
    }
}
