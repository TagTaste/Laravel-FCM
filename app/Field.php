<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    protected $fillable = ['name','description'];
    
    protected $visible = ['id','name','description'];
    
    
    public static function getChildrenOf($parentId)
    {
        return self::where('parent_id',$parentId)->get();
    }
    
    public function getChildrenAttribute()
    {
        return $this->children();
    }
}
