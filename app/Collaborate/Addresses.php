<?php

namespace App\Collaborate;

use Illuminate\Database\Eloquent\Model;


class Addresses extends Model {

    protected $table = 'collaborate_addresses';

    protected $fillable = ['city','location','collaborate_id'];

    protected $visible = ['city','collaborate_id','locationJson'];

    protected $appends = ['locationJson'];

    private $recursive_count = 0, $retArray = array();
    public function getLocationJsonAttribute()
    {
        if(isset($this->location))
        {
            $locationArray = json_decode($this->location,true);
            $recurciveReturn = $this->getLocationJSONRecursiveFunction($locationArray);
            return $this->retArray;
        }
    }
    public function getLocationJSONRecursiveFunction($locationArray)
    {
        $tempCount = ++$this->recursive_count;
        $start_date = "start_date_" . (string)$tempCount;
        $location = "location_" . (string)$tempCount;
        $end_date = "end_date_" . (string)$tempCount;
        if(array_key_exists($start_date,$locationArray)){
            $temp_array = array(
                "start_date" => $locationArray[$start_date],
                "end_date" => isset($locationArray[$end_date]) ? $locationArray[$end_date] : "",
                "location" => isset($locationArray[$location]) ? $locationArray[$location] : ""
            );
            array_push($this->retArray,$temp_array);

        $this->getLocationJsonAttribute($locationArray);
        }else{
            return true;
        }
    }

}
