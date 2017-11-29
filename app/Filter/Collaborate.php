<?php

namespace App\Filter;

use App\Filter;

class Collaborate extends Filter
{
    protected $table = "collaborate_filters";
    
    protected $csv = ['keywords'];
    
    protected $strings = ['location','starts in'=>'start_in','duration'];
    
    protected $models = ['By Company'=>'company.name','By People' => 'profile.name'];
    
    public static $cacheKey = "collaborate:";
    
    public static $relatedColumn = 'collaborate_id';
    
    public function getprofile_nameattribute($model)
    {
        if(!$model->company_id){
           return $model->profile->name;
        }
    }
}