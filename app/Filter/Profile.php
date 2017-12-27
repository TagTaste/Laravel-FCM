<?php

namespace App\Filter;

use App\Filter;

class Profile extends Filter {

    protected $table = "profile_filters";
    
    protected $csv = ['skills'=>'keywords','language'=>'expertise','affiliations'];

    protected $strings = ['location'=>'city'];

    protected $models = ['education.college','Work experience'=>'experience.company'];
    
    public static $cacheKey = "profile:small:";
    
    public static $relatedColumn = 'profile_id';

    public static $filterOrder = ['skills','education','experience','location','affiliations','language'];


}