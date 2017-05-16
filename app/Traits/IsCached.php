<?php
namespace App\Traits;

/**
 * Returns the cached key of the model.
 *
 * Class isCached
 * @package App\Traits
 */
trait IsCached
{
    public function getCacheKey() : array
    {
        $name = strtolower(class_basename($this));
        return [$name => $name . ":" . $this->id];
    }
}