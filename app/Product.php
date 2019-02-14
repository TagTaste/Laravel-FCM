<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Product extends Model
{
    use SoftDeletes;

	static $imagePath = 'app/product_images/';

	public static $types = ['Vegetarian','Non-Vegetarian', 'Vegan'];

	public static $modes = ['Frozen','Fresh'];

    protected $fillable = ['name','price','image','moq'];

    protected $dates = ['deleted_at'];

    public function user(){
    	return $this->belongsTo('\App\User');
    }

    public function profileType(){
    	return $this->belongsTo('\App\ProfileType');
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
