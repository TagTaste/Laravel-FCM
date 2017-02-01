<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ideabook extends Model
{
    use SoftDeletes;

    protected $fillable = ['name','description','privacy_id','user_id'];

    protected $dates = ['deleted_at'];

    public static function boot()
    {
        parent::boot();

        self::deleting(function($ideabook){
            if($ideabook->articles->count()){
                $ideabook->articles->delete();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo('\App\User');
    }

    public function privacy()
    {
        return $this->belongsTo('\App\Privacy');
    }

    public function articles()
    {
        return $this->belongsToMany('\App\Article','ideabook_articles','ideabook_id','article_id');
    }

    public function albums()
    {
        return $this->belongsToMany('\App\Album','ideabook_albums','ideabook_id','album_id');
    }

    public function photos()
    {
        return $this->belongsToMany('\App\Photo','ideabook_photos','ideabook_id','photo_id');
    }

    public function scopeProfile($query,$profileId)
    {
       return $query->whereHas('user.profile',function($query) use ($profileId){
           return $query->where('profiles.id',$profileId);
       });
    }

}
