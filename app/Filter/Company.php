<?php

namespace App\Filter;

use App\Company as BaseCompany;

class Company extends BaseCompany {
    protected $with = [];
    protected $visible = ['registered_address','employee_count'];
    protected $appends = [];
}