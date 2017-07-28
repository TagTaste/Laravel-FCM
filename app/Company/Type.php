<?php

namespace App\Company;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $table = "company_types";

    protected $visible = ['id','name','value','key'];
}
