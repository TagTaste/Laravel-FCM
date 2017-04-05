<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $visible = ['name','content','id'];
    protected $appends = ['name'];

    public function photo()
    {
        return $this->belongsToMany('App\Photo','comments_photos','comment_id','photo_id')->withPivot('photo_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getNameAttribute()
    {
        return $this->user->name;
    }
    
    public function scopeForPhoto($query,$id)
    {
        return $query->whereHas("photo",function($query) use ($id){
            $query->where('photo_id',$id);
    });
    }
}
