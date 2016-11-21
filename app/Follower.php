<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    public function chef() {
    	return $this->belongsTo('\App\User');
    }

    public function follower() {
    	return $this->belongsTo('App\User');
    }
}
