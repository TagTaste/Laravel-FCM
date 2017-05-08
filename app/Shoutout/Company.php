<?php

namespace App\Shoutout;

use App\Company as BaseCompany;

class Company extends BaseCompany
{
    
    protected $fillable = [];
    
    protected $visible = [ 'id', 'name', 'about', 'logo', 'tagline' ];
    
    protected $with = [];

    protected $appends = [];
}
