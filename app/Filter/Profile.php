<?php

namespace App\Filter;

use App\Filter;

class Profile extends Filter {

    protected $table = "profile_filters";
    
    protected $csv = ['skills'=>'keywords','language'=>'expertise','affiliations'];
    
    protected $strings = ['location'];
    
    protected $models = ['education.college','experience.company'];
    
    public static $cacheKey = "profile:small:";
    
    public static $relatedColumn = 'profile_id';

}