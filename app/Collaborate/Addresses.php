<?php

namespace App\Collaborate;

use Illuminate\Database\Eloquent\Model;


class Addresses extends Model {

    protected $table = 'collaborate_addresses';

    protected $fillable = ['city','location','collaborate_id'];

    protected $visible = ['city','collaborate_id','locationJson'];

    protected $appends = ['locationJson'];

    public function getLocationJsonAttribute()
    {
        if(isset($this->location))
        {
            $data = [];
            $location = json_decode($this->location,true);
            $locations = array_chunk($location, count($location)/3);
            for($i = 0; $i<count($location)/3; $i++)
            {
                $object['start_date'] = $locations[0][$i];
                $object['location'] = $locations[1][$i];
                $object['end_date'] = $locations[2][$i];
                array_push($data,$object);
                unset($object);
            }
            return $data;
        }
    }

}
