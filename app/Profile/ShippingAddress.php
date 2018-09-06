<?php

namespace App\Profile;

use Illuminate\Database\Eloquent\Model;

class ShippingAddress extends Model
{
    protected $table = 'profile_addresses';

    protected $fillable = ['pincode','city','state','address1','country','label','profile_id','landmark','locality','house_no'];

    protected $visible = ['id','pincode','city','state','address1','country','label','profile_id','landmark','locality','house_no'];


    public function profile()
    {
    	return $this->belongsTo('App\Profile');
    }
}
