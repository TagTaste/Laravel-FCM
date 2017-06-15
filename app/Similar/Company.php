<?php

namespace App\Similar;

use App\Company as BaseCompany;

class Company extends BaseCompany
{
    //protected $visible = ['id'];
    
    public function similar($skip,$take)
    {
        return self::skip($skip)
            ->take($take)
            ->get();
    }
}
