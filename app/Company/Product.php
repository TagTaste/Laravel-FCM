<?php

namespace App\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Product extends Model
{
    use SoftDeletes;

	static public $imagePath = 'app/product_images/';

	public static $types = ['Vegetarian','Non-Vegeratrian', 'Vegan'];

	public static $modes = ['Frozen','Fresh'];

    protected $fillable = ['name','price','image','moq'];

    protected $dates = ['deleted_at'];
    
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
}
