<?php

namespace App\Company;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'company_addresses';

    protected $fillable = ['address','country','phone','company_id'];

    protected $visible = ['id','address','country','phone','count'];
    
    protected $appends = ['count'];
    
    public function getCountAttribute()
    {
        return $this->where('company_id',$this->company_id)->count();
    }
}
