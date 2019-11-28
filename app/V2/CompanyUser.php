<?php

namespace App\V2;

use Illuminate\Database\Eloquent\Model;
use App\CompanyUser as BaseCompanyUser;

class CompanyUser extends BaseCompanyUser
{
    protected $table = 'company_users';

    protected $visible = ['profile'];

    protected $with = ['profile'];
    
    public function profile()
    {
        return $this->belongsTo(\App\V2\Profile::class,'profile_id');
    }
}
