<?php

namespace App\Company;

use App\Company;
use Illuminate\Database\Eloquent\Model;

class Patent extends Model
{
    protected $table = 'company_patents';

    protected $fillable = ['title','description','awarded_on','issued_by','number','company_id'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
