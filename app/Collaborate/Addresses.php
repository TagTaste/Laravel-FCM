<?php

namespace App\Collaborate;

use Illuminate\Database\Eloquent\Model;


class Addresses extends Model {

    protected $table = 'collaborate_addresses';

    protected $fillable = ['city','location','collaborate_id'];

    protected $visible = ['city','location','collaborate_id','locationJson'];

    protected $appends = ['locationJson'];

    public function getLocationJsonAttribute()
    {
        if(isset($this->location))
        {
            return json_decode($this->location,true);
        }
    }

}
