<?php

namespace App\Collaboration;

use Illuminate\Database\Eloquent\Model;

class Collaborator extends Model
{
    protected $table = 'collaborators';
    
    protected $primaryKey = 'collaborate_id';
    
    public $incrementing = false;
    public $timestamps = false;
    
    public function profile()
    {
        return $this->belongsTo(\App\Recipe\Profile::class);
    }
    
    public function company()
    {
        return $this->belongsTo(\App\Company::class);
    }

    public function collaborate()
    {
        return $this->belongsTo(\App\Collaborate::class);
    }
}
