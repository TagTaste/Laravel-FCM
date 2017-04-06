<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Comment extends Model
{
    protected $visible = ['name','content','id','profileImage','created_at'];
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
    
    public function getCreatedAtAttribute()
    {
        $createdAt =new Carbon($this->attributes['created_at']);
        return $createdAt->diffForHumans();
    }
    
    public function scopeForPhoto($query,$id)
    {
        return $query->whereHas("photo",function($query) use ($id){
            $query->where('photo_id',$id);
    });
    }
}
