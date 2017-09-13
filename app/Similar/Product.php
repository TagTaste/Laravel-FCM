<?php

namespace App\Similar;

use App\Product as BaseProduct;

class Product extends BaseProduct
{
    //protected $visible = ['id'];
    
    public function similar($skip,$take)
    {
        return self::where('type','like',$this->type)
            ->orWhere('mode','like',$this->mode)
            ->whereNull('deleted_at')
            ->skip($skip)
            ->take($take)
            ->get();
    }
}
