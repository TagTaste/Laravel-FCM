<?php

namespace App\Company;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    protected $fillable = ['worked_for','description','company_id'];
    
    protected $appends = ['total'];
    
    public function company()
    {
        return $this->belongsTo('App\Company');
    }
    
    public function getTotalAttribute()
    {
        return $this->where('company_id',$this->company_id)->count();
    }
}
