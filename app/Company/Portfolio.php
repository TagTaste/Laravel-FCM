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
        $collection = $this->select('id')->where('company_id',$this->company_id)->orderBy('created_at','asc')->get();
        $count = $this->getCount($collection);
        return $count;
        
    }
}
