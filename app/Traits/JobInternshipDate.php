<?php
/**
 * Created by PhpStorm.
 * User: Manda
 * Date: 26/12/17
 * Time: 12:27 PM
 */

namespace App\Traits;


trait JobInternshipDate
{
    public function setStartMonthAttribute($value)
    {
        if(empty($value)) {
            $this->attributes['start_month'] = null;
        }
        else
        {
            $this->attributes['start_month'] = $value;
        }
    }

    public function setStartYearAttribute($value)
    {
        if(empty($value)) {
            $this->attributes['start_year'] = null;
        }
        else
        {
            $this->attributes['start_year'] = $value;
        }
    }

    public function setEndMonthAttribute($value)
    {
        if(empty($value)) {
            $this->attributes['end_month'] = null;
        }
        else
        {
            $this->attributes['end_month'] = $value;
        }
    }

    public function setEndYearAttribute($value)
    {
        if(empty($value)) {
            $this->attributes['end_year'] = null;
        }
        else
        {
            $this->attributes['end_year'] = $value;
        }
    }

}