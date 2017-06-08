<?php

namespace App\Shareable;

use App\Shareable\Share;
use App\Comment;

class Collaborate extends Share
{
    protected $fillable = ['profile_id','collaborate_id','payload_id'];
    protected $visible = ['id','profile_id','created_at'];


    public function comments()
    {
        return $this->belongsToMany(Comment::class,'comments_collaborate_shares','collaborate_Share_id','comment_id');
    }

}
