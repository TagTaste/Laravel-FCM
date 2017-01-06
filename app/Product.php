<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
	static $imagePath = 'app/product_images/';

	public static $types = ['Vegetarian','Non-Vegeratrian', 'Vegan'];

	public static $modes = ['Frozen','Fresh'];

    protected $fillable = ['name','price','image','moq'];

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
