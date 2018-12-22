<?php

namespace App;

use App\Traits\GetTags;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes, GetTags;
    protected $visible = ['name','content','id','profile_id','profileImage','created_at','has_tags','handle','profile_image_meta'];
    protected $appends = ['name','profileImage','profile_id','count','handle','profile_image_meta'];

    public function review()
    {
        return $this->belongsToMany('App\PublicReviewProduct\Review','comments_public_review','comment_id','public_review_id')->withPivot('public_review_id');
    }

    public function recipe()
    {
        return $this->belongsToMany('App\Recipe','comments_recipes','comment_id','recipe_id')->withPivot('recipe_id');
    }

    public function photo()
    {
        return $this->belongsToMany('App\Photo','comments_photos','comment_id','photo_id')->withPivot('photo_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
    
    public function collaborate()
    {
        return $this->belongsToMany(Collaborate::class,'comments_collaborates','comment_id','collaborate_id')->withPivot('collaborate_id');
    }

    public function getNameAttribute()
    {
        return $this->user->name;
    }
    
    public function getProfileImageAttribute()
    {
        return $this->user->profile->imageUrl;
    }

    public function getProfileImageMetaAttribute()
    {
        return $this->user->profile->image_meta;
    }

    public function getHandleAttribute()
    {
        return $this->user->profile->handle;
    }
    
    public function scopeForPhoto($query,$id)
    {
        return $query->whereHas("photo",function($query) use ($id){
            $query->where('photo_id',$id);
    });
    }
    
    public function getProfileIdAttribute()
    {
        return $this->user->profile->id;
    }

    public function getMetaFor(&$model)
    {
        $meta = [];
        $meta['commentCount'] = $model->comments()->count();

        return $meta;
    }
    
    public function getContentAttribute($value)
    {
        $profiles = $this->getTaggedProfiles($value);
        
        if($profiles){
            $value = ['text'=>$value,'profiles'=>$profiles];
        }
        return $value;
    }

}
