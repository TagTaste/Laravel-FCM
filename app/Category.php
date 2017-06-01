<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [ 'name', 'parent_id'];
    
    protected $visible=['id','name'];
    public static function checkExistCategory($catData){
        $category=0;
        if(array_key_exists('parent_id', $catData))
		    $category = Category::where('parent_id', $catData['parent_id'])->Where('name',$catData['name'])->exists();
        else
            $category = Category::Where('name',$catData['name'])->exists();
		if($category)
			throw new \Exception("This category already exists");
	}
    
}
