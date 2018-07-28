<?php

namespace App\Profile;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    //
    protected $fillable = ['pincode','city','state','address1','country','label','profile_id'];

    protected $visible = ['pincode','city','state','address1','country','label','profile_id'];



    public function profile()
    {
    	return $this->belongsTo('App\Profile');
    }
}
