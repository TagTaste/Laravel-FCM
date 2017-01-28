<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    public function follower() {
    	return $this->belongsTo('\App\Profile');
    }

    public function follows() {
    	return $this->belongsTo('App\User');
    }
}
