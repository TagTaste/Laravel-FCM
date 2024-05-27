<?php

namespace App\Traits;

trait SecondsToTime
{
    function secondsToTime($seconds)
    {
        $s = $seconds % 60;
        $m = floor(($seconds % 3600) / 60);
        $h = floor(($seconds % 86400) / 3600);
        $d = floor(($seconds % 2592000) / 86400);
        $M = floor($seconds / 2592000);
        
        $durationStr = "";
        if ($M > 0) {
            $durationStr .= $M."m ";
        }

        if ($d > 0) {
            $durationStr .= $d."d ";
        }

        if ($h > 0) {
            $durationStr .= $h."h ";
        }

        if ($m > 0) {
            $durationStr .= $m."m ";
        }

        if ($s > 0) {
            $durationStr .= $s."s";
        }

        return $durationStr;
    }
}