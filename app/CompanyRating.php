<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyRating extends Model
{
    public $timestamps = false;

    protected $fillable = ['company_id','profile_id','rating'];

    protected $visible = ['rating','companyRating'];

    protected $appends =['companyRating'];

    public function getCompanyRatingAttribute(){
        return $this->where('company_id',$this->company_id)->avg('rating');
    }

}
