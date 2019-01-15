<?php

namespace App\Shareable;

use App\PeopleLike;
use App\Shareable\Share;

class Product extends Share
{

    protected $with = ['product'];

    public static function boot()
    {
        static::deleted(function($model){
            $model->payload->delete();
        });
    }

    public function publicreviewproduct()
    {
        return $this->belongsTo(\App\PublicReviewProduct::class,'product_id');
    }

}
