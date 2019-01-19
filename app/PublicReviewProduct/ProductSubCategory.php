<?php

namespace App\PublicReviewProduct;

use Illuminate\Database\Eloquent\Model;

class ProductSubCategory extends Model
{
    protected $table = 'product_sub_categories';

    protected $fillable = ['name','is_active'];

    protected $visible = ['id','name','is_active'];


}
