<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class CollaborateCategory extends Model
{
    protected $fillable = ['name', 'parent_id'];

    protected $visible = ['id', 'name','children','key','value'];

    public static function checkExists(&$collabCategoryDetails)
    {
        $category = CollaborateCategory::where('name', $collabCategoryDetails['name']);

        if (array_key_exists('parent_id', $collabCategoryDetails)) {
            $category = $category->where('parent_id', $collabCategoryDetails['parent_id']);
        }

        return $category->exists();
    }

    public function children()
    {
        return $this->hasMany(self::class,'parent_id','id');
    }

}
