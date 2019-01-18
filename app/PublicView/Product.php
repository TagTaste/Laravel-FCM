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
        'product_category','product_sub_category','type','overall_rating','current_status','created_at','updated_at','deleted_at','keywords',
        'is_authenticity_check','user_review'];



    protected $appends = ['type','user_review'];

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

    public function getUserReview()
    {
        $header = BasePublicReviewProduct\ReviewHeader::where('global_question_id',$this->global_question_id)->where('header_selection_type',2)->first();
        return BasePublicReviewProduct\Review::where('product_id',$this->id)->where('header_id',$header->id)
            ->where('select_type',5)->orderBy('updated_at','DESC')->skip(0)->take(5)->get();
    }

}
