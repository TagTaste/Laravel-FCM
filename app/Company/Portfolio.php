<?php

namespace App\Company;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    protected $fillable = ['worked_for','description','company_id'];
    
    public function company()
    {
        return $this->belongsTo('App\Company');
    }
}
