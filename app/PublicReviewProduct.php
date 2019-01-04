<?php

namespace App;

use App\PublicReviewProduct\Review;
use App\PublicReviewProduct\ReviewHeader;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class PublicReviewProduct extends Model
{

    use SoftDeletes;

    public $incrementing = false;

    protected $table = 'public_review_products';

    protected $dates = ['deleted_at'];

    public static $types = ['Vegetarian','Non-Vegeratrian'];

        protected $fillable = ['id','name','is_vegetarian','product_category_id','product_sub_category_id','brand_name','brand_logo',
        'company_name','company_logo','company_id','description','mark_featured','images_meta','video_link', 'global_question_id',
            'is_active','created_at','updated_at','deleted_at'];

    protected $visible = ['id','name','is_vegetarian','product_category_id','product_sub_category_id','brand_name','brand_logo',
        'company_name','company_logo','company_id','description','mark_featured','images_meta','video_link','global_question_id','is_active',
        'product_category','product_sub_category','type','overall_rating','is_reviewed','created_at','updated_at','deleted_at'];

    protected $appends = ['type','overall_rating','is_reviewed'];

    protected $with = ['product_category','product_sub_category'];

    public static function boot()
    {
        self::created(function($model){
            \App\Documents\PublicReviewProduct::create($model);
        });

        self::updated(function($model){
            //update the search
            \App\Documents\PublicReviewProduct::create($model);

        });
    }

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

    public function getImagesMetaAttribute($value)
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
        if($value == 0 || is_null($value))
            return null;
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
        $header = ReviewHeader::where('global_question_id',$this->global_question_id)->where('header_selection_type',2)->first();
        if($header != null)
        {
            $overallPreferances = \DB::table('public_product_user_review')->where('product_id',$this->id)->where('header_id',$header->id)->where('select_type',5)->sum('leaf_id');
            $userCount = \DB::table('public_product_user_review')->where('product_id',$this->id)->where('header_id',$header->id)->where('select_type',5)->get()->count();
            if($userCount >= 3)
            {
                $meta = [];
                $meta['max_rating'] = 8;
                $meta['overall_rating'] = $userCount > 0 ? $overallPreferances/$userCount : 0.00;
                $meta['count'] = $userCount;
                $meta['color_code'] = $this->getColorCode(floor($meta['overall_rating']));
                return $meta;
            }

        }

        return null;
    }

    public function getIsReviewedAttribute()
    {
        $loggedInProfileId = request()->user()->profile->id;
        return \DB::table('public_product_user_review')->where('product_id',$this->id)->where('profile_id',$loggedInProfileId)->where('current_status',1)->exists();
    }
}
