<?php

namespace App\Filter;

use App\Filter;

class Company extends Filter {
    
    protected $strings = ['location'];
    protected $csv = ['speciality'];
    protected $models = ['type.name'];
    
    protected $table = "company_filters";
    public static $cacheKey = "company:small:";
    public static $relatedColumn = 'company_id';
}