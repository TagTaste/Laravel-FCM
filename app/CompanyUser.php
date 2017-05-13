<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyUser extends Model
{
    protected $fillable = ['company_id', 'user_id'];

    protected $visiable = ['company_id', 'user_id', 'created_at', 'updated_at'];
    
}
