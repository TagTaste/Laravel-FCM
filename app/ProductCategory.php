<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $fillable = ['product_id', 'category_id'];

    public function product()
    {
        return $this->belongsToMany('App\Products', 'product_categories', 
        'category_id', 'products_id');
    }
}
