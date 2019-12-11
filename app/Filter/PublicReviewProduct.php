<?php

namespace App\Filter;

use App\Filter;

class PublicReviewProduct extends Filter {

    protected $table = "product_filters";
    
//    protected $csv = ['brand_name','company_name'];

    protected $strings = ['Product Type'=>'is_vegetarian','By Brand'=>'brand_name','By Company'=>'company_name','is_newly_launched'=>'is_newly_launched'];

    protected $models = ['Category'=>'product_category.name','Sub Category'=>'product_sub_category.name'];

    public static $relatedColumn = 'product_id';

    public static $filterOrder = ['is_newly_launched','By Brand','By Company','Category','Sub Category','Product Type'];

    public function getis_vegetarianattribute(&$model)
    {
        if($model->is_vegetarian == 1)
        {
            return 'Vegetarian';
        }
        else
        {
            return 'Non-Vegetarian';
        }
    }
}