<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $visible = ['name','content','id','profileImage'];
    protected $appends = ['name','profileImage'];

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
    
    public function getProfileImageAttribute()
    {
        return $this->user->profile->imageUrl;
    }
    
    public function scopeForPhoto($query,$id)
    {
        return $query->whereHas("photo",function($query) use ($id){
            $query->where('photo_id',$id);
    });
    }
}
