<?php

namespace App\Collaboration;

use Illuminate\Database\Eloquent\Model;

class Collaborator extends Model
{
    public $protected = 'collaborators';
    
    public function profile()
    {
        return $this->belongsTo(\App\Recipe\Profile::class);
    }
}
