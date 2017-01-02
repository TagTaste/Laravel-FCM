<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
	static $imagePath = 'app/product_images/';

    protected $fillable = ['name','price','image','moq'];
}
