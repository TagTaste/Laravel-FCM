<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    public function type() {
    	return $this->belongsTo('\App\ProfileType');
    }

    public function attribute() {
    	return $this->belongsTo('\App\ProfileAttribute','profile_attribute_id');
    }
}
