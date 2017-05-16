<?php


namespace App\Traits;


trait HasPayload
{
    public function getPayload() : array
    {
        return array_merge($this->getCacheKey(),$this->getRelatedKey());
    }
}