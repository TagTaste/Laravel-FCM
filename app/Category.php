<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['name', 'parent_id'];
    
    protected $visible = ['id', 'name'];
    
    public static function checkExists(&$categoryDetails)
    {
        $category = Category::where('name', $categoryDetails['name']);
        
        if (array_key_exists('parent_id', $categoryDetails)) {
            $category = $category->where('parent_id', $categoryDetails['parent_id']);
        }
        
        return $category->exists();
    }
    
}
