<?php

namespace App\Shareable;

use App\PeopleLike;
use App\PublicReviewProduct;
use App\PublicReviewProduct\Review;
use App\Shareable\Share;


class Product extends Share
{
    protected $fillable = ['profile_id','product_id','payload_id','privacy_id'];
    protected $visible = ['id','profile_id','product_id','created_at'];

    protected $with = ['product'];

    public function __construct($attributes = [])
    {
        $this->table = "public_review_product_shares";
        $column = strtolower(class_basename($this)).'_id';
        $this->fillable[] = $column;
    }

    public static function boot()
    {
        static::deleted(function($model){
            $model->payload->delete();
        });
    }

    public function product()
    {
        return $this->belongsTo(\App\PublicReviewProduct::class,'product_id');
    }

    public function getRelatedKey()
    {
        return [];
    }

    public function getMetaFor(int $profileId) : array
    {
        $product = PublicReviewProduct::where('id',$this->product_id)->whereNull('deleted_at')->first();
        $meta = [];
        $meta['overall_rating'] = $this->getOverallRatingAttribute($product);
        return $meta;
    }

    public function getOverallRatingAttribute($product)
    {
        $header = PublicReviewProduct\ReviewHeader::where('global_question_id',$product->global_question_id)->where('header_selection_type',2)->first();
        if($header != null)
        {
            $overallPreferances = \DB::table('public_product_user_review')->where('current_status',2)->where('product_id',$product->id)->where('header_id',$header->id)->where('select_type',5)->sum('leaf_id');
            $userCount = \DB::table('public_product_user_review')->where('current_status',2)->where('product_id',$product->id)->where('header_id',$header->id)->where('select_type',5)->get()->count();
            $meta = [];
            $meta['max_rating'] = 8;
            $meta['overall_rating'] = $userCount >= 3 ? $overallPreferances/$userCount : null;
            $meta['count'] = $userCount;
            $meta['color_code'] = $userCount >= 3 ? $this->getColorCode(floor($meta['overall_rating'])) : null;
            return $meta;
        }

        return null;
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

}
