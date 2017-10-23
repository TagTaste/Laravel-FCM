<?php

namespace App\Filter;

use App\Filter;

class Job extends Filter {
    protected $table = "job_filters";
    
    protected $csv = ['keywords','expertise'];
    
    protected $strings = ['location','expected_role','joining_time'];
    
    protected $models = ['company.name','jobType.name'];
    
    public static $cacheKey = "job:";
    
    public static $relatedColumn = 'job_id';
}