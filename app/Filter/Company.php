<?php

namespace App\Filter;

use App\Company as BaseCompany;

class Company extends BaseCompany {
    protected $with = [];
    protected $visible = ['city','employee_count','key','value'];
    protected $appends = [];
}