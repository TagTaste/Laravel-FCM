<?php

namespace App\Recipe;

use App\CollaborateCategory;
use Storage;
use App\Collaborate as BaseCollaborate;

class PublicReviewProduct extends BaseCollaborate
{
    protected $fillable = [];

    protected $visible = ['id','name','description','images_meta'];

    public function getImagesMetaAttribute($value)
    {
        if(isset($value))
        {
            return json_decode($value);
        }
        return [];
    }



}
