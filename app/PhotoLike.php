<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PhotoLike extends Model
{  
    protected $fillable = ['photo_id','profile_id'];

   

   public function photo()
   {
   	 return $this->belongsToMany('App\Photo','photo_id');
   }





   

   
}
