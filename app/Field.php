<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    protected $fillable = ['name','description'];
    
    protected $visible = ['id','name','description'];
    
    public function parents()
    {
        return self::whereNull("parent_id")->get();
    }
    
    public function children()
    {
        return self::where('parent_id',$this->id)->get();
    }
    
    public static function getChildrenOf($parentId)
    {
        return self::where('parent_id',$parentId)->get();
    }
}
