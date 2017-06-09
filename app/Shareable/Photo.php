<?php

namespace App\Shareable;

use App\Shareable\Share;

class Photo extends Share
{
    protected $fillable = ['profile_id','photo_id','payload_id'];
    protected $visible = ['id','profile_id','created_at'];

     public function comments()
    {
        return $this->belongsToMany(Comment::class,'comments_photo_shares','photo_Share_id','comment_id');
    }
}
