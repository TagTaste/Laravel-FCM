<?php

namespace App\Company;

use App\Traits\PositionInCollection;
use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    use PositionInCollection;
    
    protected $fillable = ['worked_for','description','company_id'];
    
    protected $appends = ['total'];
    
    public function company()
    {
        return $this->belongsTo('App\Company');
    }
    
    public function getTotalAttribute()
    {
        return 0;
        
    }
}
