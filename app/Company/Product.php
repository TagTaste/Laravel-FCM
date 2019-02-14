<?php

namespace App\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Product extends Model
{
    use SoftDeletes;

	static public $imagePath = 'app/product_images/';

	public static $types = ['Vegetarian','Non-Vegetarian', 'Vegan'];

	public static $modes = ['Frozen','Fresh'];

    protected $fillable = ['name','price','image','moq','imageUrl','description','certifications','delivery_cities','category','image_meta'];

    protected $dates = ['deleted_at'];
    
    protected $appends = ['imageUrl'];

//    protected $with=['categories'];

    
    
    public function company()
    {
        return $this->belongsTo(\App\Company::class);
    }

    public function getMode()
    {
        return self::$modes[$this->mode];
    }

    public function getType()
    {
        return self::$types[$this->type];
    }
    
    public function getImageUrlAttribute()
    {
        return $this->image;
    }
    public function categories()
    {
        return $this->belongsToMany('App\Category', 'product_categories','product_id');
    }

    public static function getProductImagePath($profileId,$companyId, $filename = null)
    {
        $relativePath = "images/c/$companyId/gallery";
        $status = \Storage::makeDirectory($relativePath,0644,true);
        return $filename === null ? $relativePath : $relativePath . "/" . $filename;
    }
}
