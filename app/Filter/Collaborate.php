<?php

namespace App\Filter;

use App\Filter;

class Collaborate extends Filter
{
    protected $table = "collaborate_filters";
    
    protected $csv = ['keywords'];
    
    protected $strings = ['location'];
    
    public static $cacheKey = "collaborate:";
    
    public static $relatedColumn = 'collaborate_id';
}