<?php
/**
 * Created by PhpStorm.
 * User: amitabh
 * Date: 27/01/17
 * Time: 6:19 PM
 */

namespace App\Traits;


trait StartEndDate
{
    public function setStartDateAttribute($value)
    {
        if(!empty($value)) {
            $value = "01-" . $value;
            $this->attributes['start_date'] = date('Y-m-d', strtotime($value));
        }
    }

    public function setEndDateAttribute($value)
    {
        if(!empty($value)) {
            $value = "01-" . $value;
            $this->attributes['end_date'] = date('Y-m-d', strtotime($value));
        }
    }


    public function getStartDateAttribute($value)
    {
        return date("m-Y",strtotime($value));
    }

    public function getEndDateAttribute($value)
    {
        return date("m-Y",strtotime($value));
    }

}