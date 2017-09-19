<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyUser extends Model
{
    protected $table = 'company_users';
    
    protected $fillable = ['company_id', 'user_id','profile_id'];

    protected $visible = ['profile', 'created_at', 'updated_at','company'];

    protected $with = ['profile','company'];
    
    public function profile()
    {
        return $this->belongsTo(\App\Recipe\Profile::class,'profile_id');
    }

    public function company()
    {
        return $this->belongsTo(\App\Company::class,'company_id');
    }
}
