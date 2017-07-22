<?php

namespace App\Recipe;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 'recipe_images';

    protected $fillable = ['image','recipe_id','show_case'];

    protected $visible = ['id','imageUrl','show_case'];

    protected $appends = ['imageUrl'];

    public function getImageUrlAttribute()
    {
         return $this->image !== null ? "/profile/recipes/".$this->recipe_id."/images/" . $this->id."/".$this->image : null;
    }

}
