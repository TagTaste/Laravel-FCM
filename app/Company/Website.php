<?php

namespace App\Company;

use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    protected $table = 'company_websites';

    protected $fillable = ['name','url'];

    public function company()
    {
        return $this->belongsTo('App\Company');
    }
}
