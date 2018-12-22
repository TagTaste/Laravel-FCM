<?php

namespace App\Filter;

use App\Filter;

class Product extends Filter {

    protected $table = "product_filters";
    
    protected $csv = ['is_vegetarian','brand_name','company_name'];

    //protected $strings = ['location'=>'city'];

    protected $models = ['Product'=>'product_category.name','Subcategory'=>'product_sub_category.name'];
    
    //public static $cacheKey = "profile:small:";
    
    public static $relatedColumn = 'public_review_id';

    public static $filterOrder = ['brand_name','company_name','Product','Subcategory','is_vegetarian'];
}