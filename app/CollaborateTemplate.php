<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CollaborateTemplate extends Model
{
    protected $fillable = ['name'];
    
    protected $visible = ['id','name','fields'];
    
    protected $with = ['fields'];
    
    public function fields()
    {
        return $this->belongsToMany(Field::class,'collaboration_template_fields','template_id','field_id');
    }
    
    public function syncFields($fieldIds = [])
    {
        if(empty($fieldIds)){
            \Log::warning("Empty fields passed.");
            return false;
        }
        
        $fields = Field::select('id')->whereIn('id',$fieldIds)->get();
        
        if($fields->count()){
            return $this->fields()->sync($fields->pluck('id')->toArray());
        }
        
        return false;
    }
}
