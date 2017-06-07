<?php


namespace App\Traits;


trait HasPayload
{
    public $additionalPayload = [];
    public function getPayload() : array
    {
        return array_merge($this->getCacheKey(),$this->getRelatedKey(), $this->additionalPayload);
    }
}