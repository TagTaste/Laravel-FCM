<?php

namespace App\PublicView;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\IdentifiesOwner;
use App\ReviewCollection as BaseReviewCollection;

class ReviewCollection extends BaseReviewCollection
{
    protected $visible = ['id','title','subtitle','description','backend','category_type','images_meta', 'elements'];

    public function getMetaForPublic()
    {
        $meta = [];
        return $meta;
    }
}
