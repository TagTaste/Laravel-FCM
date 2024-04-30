<?php

namespace App\Traits;

trait ScaleColor
{
    public function getRatingBasedColor($rating, $scale){
        if($rating == 0 || is_null($rating))
            return null;
        $rating_val = ($scale == 7) ? config('constant.SEVEN_SCALE_RANGE_VALUES') : config('constant.NINE_SCALE_RANGE_VALUES');
        switch ($rating) {
            case ($rating < $rating_val[0]):
                return config('constant.SCALE_RANGE_COLORS.red');
                break;
            case ($rating >= $rating_val[0] && $rating < $rating_val[1]):
                return config('constant.SCALE_RANGE_COLORS.orange');
                break;
            case ($rating >= $rating_val[1] && $rating < $rating_val[2]):
                return config('constant.SCALE_RANGE_COLORS.light_green');
                break;
            default: // $rating >= $rating_val[2]
                return config('constant.SCALE_RANGE_COLORS.dark_green');
        }
    }
}