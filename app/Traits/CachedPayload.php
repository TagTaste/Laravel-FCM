<?php

namespace App\Traits;

trait CachedPayload
{
    use HasPayload, IsCached, HasRelated;
}