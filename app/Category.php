<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'parent_id'];
    
    protected $visible = ['id', 'name'];
    
    public static function checkExists(&$categoryDetails)
    {
        $category = false;
        if (array_key_exists('parent_id', $categoryDetails)) {
            $category = Category::where('parent_id', $categoryDetails['parent_id'])->where('name', $categoryDetails['name'])->exists();
        } else {
            $category = Category::where('name', $categoryDetails['name'])->exists();
        }
        return $category;
    }
    
}
