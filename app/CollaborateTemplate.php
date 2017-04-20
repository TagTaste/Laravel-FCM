<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CollaborateTemplate extends Model
{
    protected $fillable = ['name', 'fields'];
    
    public function setFieldsAttribute($value)
    {
        $this->attributes['fields'] = json_encode($value);
    }
    
    public function getFieldsAttribute()
    {
        return json_decode($this->fields,true);
    }
}
