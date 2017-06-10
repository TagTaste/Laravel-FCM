<?php

namespace App\Shareable;

use App\Shareable\Share;
use App\Comment;

class Collaborate extends Share
{
    protected $fillable = ['profile_id','collaborate_id','payload_id'];
    protected $visible = ['id','profile_id','created_at'];


    

   

}
