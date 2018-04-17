<?php

namespace App\PublicView;

use App\Traits\IdentifiesOwner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Recipe as BaseRecipe;

class Recipe extends BaseRecipe
{
    use SoftDeletes, IdentifiesOwner;

    public static $expectsFiles = true;

    protected $visible = ['id','name','description','serving',
        'preparation_time','cooking_time','level','tags','likeCount','type',
        'created_at','pivot','ingredients','equipments','images','directions','rating','cuisine_id','profile_id',
        'tutorial_link','cuisine','is_vegetarian','updated_at','owner'];

    protected $appends = ['owner'];


    public function getOwnerAttribute()
    {
        return $this->owner();
    }

    public function profile()
    {
        return $this->belongsTo(\App\PublicView\Profile::class);
    }

    public function ingredients()
    {
        return $this->hasMany('App\Recipe\Ingredient');
    }

    public function equipments()
    {
        return $this->hasMany('App\Recipe\Equipment');
    }

    public function images()
    {
        return $this->hasMany('App\Recipe\Image');
    }

    public function rating()
    {
        return $this->hasMany(RecipeRating::class,'recipe_id');
    }

    public function getRatingAttribute()
    {
        return $this->rating()->avg('rating');
    }

    /**
     * @return array
     */
    public function getFillable(): array
    {
        return $this->fillable;
    }

    public function cuisine()
    {
        return $this->belongsTo(Cuisine::class);
    }

    public function getMetaForPublic()
    {
        $meta = [];
        $key = "meta:recipe:likes:" . $this->id;
        $meta['likeCount'] = \Redis::sCard($key);
        $meta['commentCount'] = \DB::table('comments_recipes')->where('recipe_id')->count();
        return $meta;
    }
}
