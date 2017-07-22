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
        return $this->image !== null ? "images/r/" . $this->recipe_id . "/" . $this->image: null;
    }
    
    public static function getImagePath($recipeId,$filename = null)
    {
        //$relativePath = "profile/{$id}/images";
        $relativePath = "images/r/$recipeId";
        
        Storage::makeDirectory($relativePath);
        return $filename === null ? $relativePath : storage_path("app/" . $relativePath) . "/" . $filename;
    }

}
