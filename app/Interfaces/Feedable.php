<?php

namespace App\Interfaces;

interface Feedable extends ProvidesModelOwner
{
    public function getCacheKey() : array;
    
    public function getRelatedKey() : array;
    
    public function getPayload() : array;
    
}