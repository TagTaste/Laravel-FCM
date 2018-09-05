<?php

namespace App\Collaborate;

use Illuminate\Database\Eloquent\Model;


class Addresses extends Model {

    protected $table = 'collaborate_addresses';

    protected $fillable = ['collaborate_id','city_id','no_of_taster'];

    protected $visible = ['id','city','state','region','no_of_taster'];

    protected $appends = ['id','city','state','region'];

    protected $cities = null;

    public function getIdAttribute()
    {
        $this->cities = \DB::table('cities')->where('id',$this->city_id)->first();
        return isset($this->cities->id) ? $this->cities->id : null;
    }

    public function getCityAttribute()
    {
        return isset($this->cities->city) ? $this->cities->city : null;
    }

    public function getStateAttribute()
    {
        return isset($this->cities->state) ? $this->cities->state : null;
    }

    public function getRegionAttribute()
    {
        return isset($this->cities->region) ? $this->cities->region : null;
    }

}
