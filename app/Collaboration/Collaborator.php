<?php

namespace App\Collaboration;

use Illuminate\Database\Eloquent\Model;

class Collaborator extends Model
{
    public $protected = 'collaborators';

    public function collaborate()
    {
        return $this->belongsTo(\App\Collaborate::class);
    }
}
