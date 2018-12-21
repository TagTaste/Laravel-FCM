<?php

namespace App\PublicReviewProduct;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $table = 'product_categories';

    protected $fillable = ['name','is_active','image'];

    protected $visible = ['id','name','is_active','image'];


}
