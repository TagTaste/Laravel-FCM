<?php

namespace App\Recipe;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 'recipe_images';

    protected $fillable = ['image','recipe_id','showCase'];

    protected $visible = ['id','imageUrl','showCase'];

    protected $appends = ['imageUrl'];

    public function getimageUrlAttribute()
    {
         return $this->image !== null ? "/profile/recipes/".$this->id."/images/" . $this->image : null;
    }

}
