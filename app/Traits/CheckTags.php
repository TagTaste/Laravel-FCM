<?php


namespace App\Traits;


trait CheckTags
{
    private function hasTags(&$content){
        return preg_match('/@\[([0-9]*):([0-9]*)\]/i',$content);
    }
}