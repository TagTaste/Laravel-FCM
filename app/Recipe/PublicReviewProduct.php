<?php

namespace App\Recipe;

use App\PublicReviewProduct as BasePublicReviewProduct;

class PublicReviewProduct extends BasePublicReviewProduct
{
    protected $fillable = [];

    protected $visible = ['id','name','description','images_meta'];

    public function getImagesMetaAttribute($value)
    {
        if(isset($value))
        {
            return json_decode($value);
        }
        return [];
    }



}
