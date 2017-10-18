<?php

namespace App\Filter;

use App\Filter;

class Profile extends Filter {

    protected $table = "profile_filters";
    
    private $csv = ['keywords','expertise'];
    
    private $strings = ['location'];
    
    public static $cacheKey = "profile:small:";
    
    public static $relatedColumn = 'profileId';

}