<?php

namespace App\Company;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $table = "company_statuses";

    protected $visible = ['id','name','key','value'];
}
