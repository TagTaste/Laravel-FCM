<?php


namespace App\Scopes;


trait Company
{
    public function scopeForCompany($query,$companyId)
    {
        return $query->whereHas('company',function($query) use ($companyId){
            $query->where('company_id',$companyId);
        });
    }
}