<?php

namespace App\Shareable;

use App\PeopleLike;
use App\Shareable\Share;

class Product extends Share
{

    protected $with = ['product'];

    public function __construct($attributes = [])
    {
        $this->table = "public_review_product_shares";
        $column = strtolower(class_basename($this)).'_id';
        $this->fillable[] = $column;
    }

    public static function boot()
    {
        static::deleted(function($model){
            $model->payload->delete();
        });
    }

    public function product()
    {
        return $this->belongsTo(\App\PublicReviewProduct::class,'product_id');
    }

}
