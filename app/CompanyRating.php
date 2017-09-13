<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyRating extends Model
{
    public $timestamps = false;

    protected $fillable = ['company_id','profile_id','rating','review'];

    protected $visible = ['rating','profile','review'];

    protected $with = ['profile'];

    public function profile()
    {
        return $this->belongsTo('App\Recipe\Profile');
    }
}
