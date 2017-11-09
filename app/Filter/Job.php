<?php

namespace App\Filter;

use App\Filter;

class Job extends Filter {
    protected $table = "job_filters";
    
    protected $csv = ['keywords','expertise'];
    
    protected $strings = ['location','expected_role','joining_time'];
    
    protected $models = ['company.name','type' => 'jobType.name','profile.name'];
    
    public static $cacheKey = "job:";
    
    public static $relatedColumn = 'job_id';
    
    public function getprofile_nameattribute($model)
    {
        if(!$model->company_id){
            return $model->profile->name;
        }
    }
}