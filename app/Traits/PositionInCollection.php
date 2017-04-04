<?php


namespace App\Traits;


trait PositionInCollection
{
    private function getCount($collection)
    {
        if($collection->count() == 1){
            return 1;
        }
        $count = 1;
        foreach($collection as $model){
            if($model->id == $this->id){
                \Log::info($count);
                return $count;
            }
            $count++;
        }
    }
}