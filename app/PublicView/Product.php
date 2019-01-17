<?php

namespace App\PublicView;

use App\Traits\IdentifiesOwner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\PublicReviewProduct as BasePublicReviewProduct;

class Product extends BasePublicReviewProduct
{
    use IdentifiesOwner, SoftDeletes;

    protected $visible = ['id','name','is_vegetarian','product_category_id','product_sub_category_id','brand_name','brand_logo',
        'company_name','company_logo','company_id','description','mark_featured','images_meta','video_link','global_question_id','is_active',
        'product_category','product_sub_category','type','overall_rating','current_status','created_at','updated_at','deleted_at','keywords','is_authenticity_check'];



    protected $appends = ['type'];

    protected $with = ['product_category','product_sub_category'];

    public function getTypeAttribute()
    {
        if($this->is_vegetarian == 1)
        {
            return ['id'=>1,'value'=>'Vegetarian','image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/public-review/kind_vegeratian_icon.png'];
        }
        else
        {
            return ['id'=>2,'value'=>'Non-Vegeratrian','https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/public-review/kind_non_vegeratian_icon.png'];
        }
    }

    public function product_category()
    {
        return $this->belongsTo(\App\PublicReviewProduct\ProductCategory::class);
    }

    public function product_sub_category()
    {
        return $this->belongsTo(\App\PublicReviewProduct\ProductSubCategory::class);
    }

    public function getMetaForPublic()
    {
        $meta = [];
        $meta['overall_rating'] = $this->getOverallRatingAttribute();
        return $meta;
    }

}
