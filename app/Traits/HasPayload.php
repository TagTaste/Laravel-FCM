<?php


namespace App\Traits;


trait HasPayload
{
    public $additionalPayload = [];
    public function getPayload() : array
    {
//        $this->getCacheKey(), //becoz don't need data for share feed
        return array_merge($this->getRelatedKey(), $this->additionalPayload);
    }
}