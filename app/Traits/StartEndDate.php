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
        $this->attributes['start_date'] = date('Y-m-d',strtotime($value));
    }

    public function getStartDateAttribute($value)
    {
        if(!$value){
            return;
        }
        return date("d-m-Y",strtotime($value));
    }

    public function setEndDateAttribute($value)
    {
        \Log::info($value);
        $this->attributes['end_date'] = date('Y-m-d',strtotime($value));
    }

    public function getEndDateAttribute($value)
    {
        if(!$value){
            return;
        }
        return date("d-m-Y",strtotime($value));
    }
}