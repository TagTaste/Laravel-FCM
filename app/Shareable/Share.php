<?php

namespace App\Shareable;

use Illuminate\Database\Eloquent\Model;

class Share extends Model
{
    public function __construct($attributes = [])
    {
        $class = strtolower(class_basename($this));
        $this->table = $class . "_shares";
        parent::__construct($attributes);
    }
}
