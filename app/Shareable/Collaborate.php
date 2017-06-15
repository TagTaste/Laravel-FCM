<?php

namespace App\Shareable;

class Collaborate extends Share
{
    protected $fillable = ['profile_id','collaborate_id','payload_id','privacy_id'];
    protected $visible = ['id','profile_id','created_at'];


    

   

}
