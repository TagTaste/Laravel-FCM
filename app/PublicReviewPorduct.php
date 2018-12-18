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
        'product_category','product_sub_category','type','overall_rating'];

    protected $appends = ['type','overall_rating'];

    protected $with = ['product_category','product_sub_category'];

    public function getTypeAttribute()
    {
        if($this->is_vegetarian == 1)
        {
            return ['id'=>1,'value'=>'Vegetarian'];
        }
        else
        {
            return ['id'=>2,'value'=>'Non-Vegeratrian'];
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

    public function getImagesAttribute($value)
    {
        if(isset($value))
        {
            return json_decode($value);
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

    protected function getColorCode($value)
    {
        switch ($value) {
            case 1:
                return '#8C0008';
                break;
            case 2:
                return '#D0021B';
                break;
            case 3:
                return '#C92E41';
                break;
            case 4:
                return '#E27616';
                break;
            case 5:
                return '#AC9000';
                break;
            case 6:
                return '#7E9B42';
                break;
            case 7:
                return '#577B33';
                break;
            default:
                return '#305D03';
        }
    }

    public function getOverallRatingAttribute()
    {
        $overallPreferances = \DB::table('public_product_user_review')->where('product_id',$this->product_id)->where('select_type',5)->sum('value');
        $userCount = \DB::table('public_product_user_review')->where('product_id',$this->product_id)->where('select_type',5)->count();
        $meta = [];
        $meta['max_rating'] = 8;
        $meta['overall_rating'] = $userCount > 0 ? $overallPreferances/$userCount : 0.00;
        $meta['count'] = $userCount;
        $meta['color_code'] = $this->getColorCode($meta['overall_rating']);
        return $meta;
    }
}
