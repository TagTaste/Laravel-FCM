<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recipe extends Model
{
    use SoftDeletes;

    protected $fillable = ['id','showcase','description','content', 'ingredients',
        'category', 'serving', 'calorie', 'time', 'image','profile_id','pivot'];

    protected $dates = ['deleted_at'];

    protected $visible = ['description','ingredients','imageUrl','category','serving', 'calorie', 'time'];

    public static $expectsFiles = true;

    public static $fileInputs = ['image' => 'recipes/images'];

    protected $appends = ['imageUrl'];

    public function profile() {
    	return $this->belongsTo('\App\Profile','chef_id');
    }

    //specific for API
    public function getImageUrlAttribute()
    {
        return "/profile/recipe/" . $this->id . '.jpg';

    }
}
