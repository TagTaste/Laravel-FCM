<?php

namespace App;

use App\Channel\Payload;
use App\Interfaces\CommentNotification;
use App\Interfaces\Feedable;
use App\Traits\CachedPayload;
use App\Traits\IdentifiesOwner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recipe extends Model
{
    use SoftDeletes, IdentifiesOwner;

    public static $expectsFiles = true;

    protected $visible = ['id','name','description','serving',
        'preparation_time','cooking_time','level','tags','likeCount','type',
        'created_at','pivot','profile','ingredients','equipments','images','directions','rating','cuisine_id',
        'tutorial_link','cuisine','is_vegetarian','updated_at'];

    public function owner()
    {
        return $this->profile;
    }

    public function getMetaFor($profileId)
    {
        $meta = [];

        return $meta;
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
}
