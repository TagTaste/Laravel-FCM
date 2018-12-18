<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class PublicReviewPorduct extends Model
{

    use SoftDeletes;

    protected $table = 'public_review_products';

    protected $dates = ['deleted_at'];

    public static $types = ['Vegetarian','Non-Vegeratrian'];

        protected $fillable = ['name','is_vegetarian','product_category_id','product_sub_category_id','brand_name','brand_logo',
        'company_name','company_logo','company_id','description','mark_featured','images','video_link', 'global_question_id','is_active'];

    protected $visible = ['id','name','is_vegetarian','product_category_id','product_sub_category_id','brand_name','brand_logo',
        'company_name','company_logo','company_id','description','mark_featured','images','video_link','global_question_id','is_active',
        'product_category','product_sub_category','type'];

    protected $appends = ['type'];

    protected $with = ['product_category','product_sub_category'];

    public function getTypeAttribute()
    {
        return self::$types[$this->is_vegetarian];
    }

    public function product_category()
    {
        return $this->belongsTo(\App\PublicReviewProduct\ProductCategory::class);
    }

    public function product_sub_category()
    {
        return $this->belongsTo(\App\PublicReviewProduct\ProductSubCategory::class);
    }

    public function getImagesAttribute($value)
    {
        $imageArray = [];
        if(isset($value))
        {
            $images = json_decode($value,true);
            foreach ($images as $image)
            {
                $imageArray[] = $image;
            }
            return $imageArray;
        }
        return [];
    }

    public function getBrandLogoAttribute($value)
    {
        if(isset($value))
            return json_decode($value);
    }

    public function getCompanyLogoAttribute($value)
    {
        if(isset($value))
            return json_decode($value);
    }
}
