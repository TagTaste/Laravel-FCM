<?php

namespace App\Similar;

use App\Product as BaseProduct;

class Product extends BaseProduct
{
    protected $visible = ['id'];
    
    public function similar()
    {
        return self::take(4)->get();
    }
}
