<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CollaborateTemplate extends Model
{
    protected $fillable = ['name'];
    
    protected $visible = ['id','name','fields','key','value'];
    
    //protected $with = ['fields'];
    
    protected $appends = ['fields'];
    
    public function getFieldsAttribute()
    {
        $parents = $this->parents();
        if($parents->count() === 0){
            return false;
        }
        $parentIds = $parents->pluck('field_id')->toArray();
        $children = $this->children($parentIds);
        
        if($children->count() === 0){
            return $parents;
        }
    
        $children = $children->groupBy('parent_field_id');
        $parents = $parents->keyBy('field_id');
        
        foreach($children as $parentId => $fields){
            $parent = $parents->get($parentId);
            $parent->children = $fields;
        }
        
        return $parents;
    }
    
    public function templateFields()
    {
        return $this->belongsToMany(Field::class,'collaboration_template_fields','template_id','field_id');
    }
    
    public function parents()
    {
        return \DB::table('collaboration_template_fields')
            ->select(['id','name','description','order','field_id'])
            ->join("fields",'fields.id','=','collaboration_template_fields.field_id')
            ->where("template_id",$this->id)
            ->whereRaw('collaboration_template_fields.parent_field_id = collaboration_template_fields.field_id')
            ->orderBy('order','inc')
            ->get();
    }
    
    public function children($parentIds = [])
    {
        return \DB::table("collaboration_template_fields")->where('template_id',$this->id)
            ->select(['id','name','description','order','parent_field_id'])
            ->join("fields",'fields.id','=','collaboration_template_fields.field_id')
            ->whereIn("parent_field_id",$parentIds)->whereNotIn('field_id',$parentIds)->get();
    }
    
    public function syncFields($fieldIds = [])
    {
        if(empty($fieldIds)){
            \Log::warning("Empty fields passed.");
            return false;
        }
        
        $fields = Field::select('id')->whereIn('id',$fieldIds)->get();
        
        if($fields->count()){
            return $this->templateFields()->sync($fields->pluck('id')->toArray());
        }
        
        return false;
    }
}
