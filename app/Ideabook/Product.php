<?php

namespace App\Ideabook;

use App\Company\Product as BaseProduct;

class Product extends BaseProduct
{
    protected $visible = ['id','name','pivot','imageUrl','company_id','profile_id'];
    
    protected $fillable = ['note'];
    
    protected $appends = ['profile_id'];
    
    public function getProfileIdAttribute()
    {
        return $this->company->user->profile->id;
    }
    
}
