<?php

namespace App\Company;

use App\Company;
use Illuminate\Database\Eloquent\Model;

class Patent extends Model
{
    protected $table = 'company_patents';

    protected $fillable = ['title','description','awarded_on','issued_by','number','company_id'];
    protected $visible = ['id','title','description','awarded_on','issued_by','number'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function setAwardedOnAttribute($value)
    {
        $this->attributes['awarded_on'] = date('Y-m-d',strtotime($value));
    }
}
