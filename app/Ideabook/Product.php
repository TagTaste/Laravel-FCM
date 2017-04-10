<?php

namespace App\Ideabook;

use App\Company\Product as BaseProduct;

class Product extends BaseProduct
{
    protected $visible = ['name','pivot','imageUrl'];
}
