<?php

namespace App\Collaboration;

use Illuminate\Database\Eloquent\Model;

class Collaborator extends Model
{
    protected $table = 'collaborators';
    
    protected $primaryKey = 'collaborate_id';

    protected $visible = ['created_at', 'profile', 'company', 'message','collaborate_id','applied_on','approved_on','rejected_on'];

    protected $with = ['profile','company'];
    
    public $incrementing = false;
    public $timestamps = false;
    
    public function profile()
    {
        return $this->belongsTo(\App\Recipe\Profile::class);
    }
    
    public function company()
    {
        return $this->belongsTo(\App\Recipe\Company::class);
    }

    public function collaborate()
    {
        return $this->belongsTo(\App\Recipe\Collaborate::class);
    }
}
