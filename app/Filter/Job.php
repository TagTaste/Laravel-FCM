<?php

namespace App\Filter;

use App\Filter;

class Job extends Filter {
    protected $table = "job_filters";

    protected $strings = ['location','expected_role','Joining'=>'joining','experience_min','experience_max'];
    
    protected $models = ['By Company'=>'company.name','Job Type' => 'jobType.name',
        'By Person'=> 'profile.name'];
    
    public static $cacheKey = "job:";
    
    public static $relatedColumn = 'job_id';

    public static $filterOrder = ['Job Type','location','By Company','By Person','Joining In'];

    public function getprofile_nameattribute($model)
    {
        if(!$model->company_id){
            return $model->profile->name;
        }
    }
}