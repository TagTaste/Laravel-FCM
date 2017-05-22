<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CollaborationField extends Model
{
    protected $fillable = ['collaboration_id', 'field_id'];
    
    public function field()
    {
        return $this->belongsTo(Field::class);
    }
    
    public function collaboration()
    {
        return $this->belongsTo(Collaborate::class);
    }
}
