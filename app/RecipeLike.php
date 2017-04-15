<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecipeLike extends Model
{
    protected $fillable = ['recipe_id','profile_id'];
    
    public function recipe()
    {
        return $this->belongsToMany('App\Recipe','recipe_id');
    }
}
