<?php

namespace App\Filter;

use App\Filter;

class Company extends Filter {
    
    protected $strings = ['location' => 'city'];
    protected $csv = ['speciality','affiliations'];
    protected $models = ['type' => 'type.name','status'=>'status.name','category'=>'products.category'];
    
    protected $table = "company_filters";
    public static $cacheKey = "company:small:";
    public static $relatedColumn = 'company_id';
    public static $filterOrder = ['speciality','location','affiliations','type','status','category'];

}