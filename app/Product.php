<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
	static $imagePath = 'app/product_images/';

    protected $fillable = ['name','price','image','moq'];

    public function user(){
    	return $this->belongsTo('\App\User');
    }

    public function profileType(){
    	return $this->belongsTo('\App\ProfileType');
    }
}
